<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\ContactSetting;
use App\Models\Due;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    public function index()
    {
        return view('admin.content.index', [
            'announcements' => Announcement::latest()->get(),
            'dues' => Due::latest()->get(),
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
            'payment_methods' => ['required', 'array', 'min:1'],
            'payment_methods.*' => ['in:qris,cash,transfer'],
            'qris_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if (in_array('qris', $data['payment_methods'], true) && ! $request->hasFile('qris_image')) {
            return back()->withErrors(['qris_image' => 'Upload gambar QRIS jika metode QRIS dipilih.'])->withInput();
        }

        if ($request->hasFile('qris_image')) {
            $directory = public_path('uploads/qris');
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = Str::uuid() . '.' . $request->file('qris_image')->extension();
            $request->file('qris_image')->move($directory, $filename);
            $data['qris_image_path'] = 'uploads/qris/' . $filename;
        }

        $data['user_id'] = $request->user()->id;
        Due::create($data);

        return back()->with('success', 'Informasi iuran berhasil ditambahkan.');
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