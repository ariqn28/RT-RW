<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\ContactSetting;
use App\Models\Due;
use App\Models\PaymentRequest;
use App\Models\DueAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    public function index()
    {
        return view('admin.content.index', [
            'announcements' => Announcement::latest()->get(),
            'dues' => Due::latest()->get(),
            'paymentRequests' => PaymentRequest::with(['due.assignments', 'user'])->latest()->get(),
            'residents' => User::where('role', 'warga')->orderBy('name')->get(),
            'contact' => ContactSetting::firstOrCreate(['id' => 1]),
        ]);
    }

    public function storeAnnouncement(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'body' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            $directory = public_path('uploads/announcements');
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = Str::uuid() . '.' . $request->file('image')->extension();
            $request->file('image')->move($directory, $filename);
            $data['image_path'] = 'uploads/announcements/' . $filename;
        }

        $data['user_id'] = $request->user()->id;
        Announcement::create($data);

        return back()->with('success', 'Berita berhasil dipublikasikan.');
    }

    public function storeDue(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'integer', 'min:0'],
            'due_date' => ['nullable', 'date'],
            'payment_info' => ['nullable', 'string', 'max:255'],
            'residents' => ['nullable', 'array'],
            'residents.*.user_id' => ['required', 'distinct', 'exists:users,id'],
            'residents.*.amount' => ['required', 'integer', 'min:0'],
        ]);

        $residents = $data['residents'] ?? [];
        unset($data['residents']);
        // Metode dibuka untuk semua tagihan. Warga memilihnya saat akan membayar.
        $data['payment_methods'] = ['qris', 'cash', 'transfer'];
        $data['user_id'] = $request->user()->id;
        $due = Due::create($data);

        foreach ($residents as $resident) {
            DueAssignment::create([
                'due_id' => $due->id,
                'user_id' => $resident['user_id'],
                'amount' => $resident['amount'],
            ]);
        }

        return back()->with('success', 'Informasi iuran berhasil ditambahkan.');
    }

    public function updateAnnouncement(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'body' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            $directory = public_path('uploads/announcements');
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = Str::uuid() . '.' . $request->file('image')->extension();
            $request->file('image')->move($directory, $filename);
            $newPath = 'uploads/announcements/' . $filename;

            if ($announcement->image_path && is_file(public_path($announcement->image_path))) {
                unlink(public_path($announcement->image_path));
            }

            $data['image_path'] = $newPath;
        }

        unset($data['image']);
        $announcement->update($data);

        return back()->with('success', 'Berita berhasil diperbarui.');
    }

    public function updateDue(Request $request, Due $due)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'integer', 'min:0'],
            'due_date' => ['nullable', 'date'],
            'payment_info' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $due->update($data);

        return back()->with('success', 'Informasi iuran berhasil diperbarui.');
    }

    public function updateContact(Request $request)
    {
        $data = $request->validate([
            'office_name' => ['required', 'string', 'max:120'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:180'],
            'address' => ['nullable', 'string'],
            'chat_greeting' => ['required', 'string', 'max:500'],
        ]);

        ContactSetting::updateOrCreate(['id' => 1], $data);

        return back()->with('success', 'Kontak pengurus berhasil diperbarui.');
    }

    public function destroyAnnouncement(Announcement $announcement)
    {
        if ($announcement->image_path) {
            $path = public_path($announcement->image_path);
            if (is_file($path)) {
                unlink($path);
            }
        }

        $announcement->delete();
        return back()->with('success', 'Berita berhasil dihapus.');
    }

    public function destroyDue(Due $due)
    {
        if ($due->qris_image_path) {
            $path = public_path($due->qris_image_path);
            if (is_file($path)) {
                unlink($path);
            }
        }

        $due->delete();
        return back()->with('success', 'Informasi iuran berhasil dihapus.');
    }
}
