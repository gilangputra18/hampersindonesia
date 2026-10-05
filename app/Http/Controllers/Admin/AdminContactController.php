<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('subject', 'like', "%{$q}%")
                    ->orWhere('message', 'like', "%{$q}%");
            });
        }

        if ($request->input('status') === 'unread') {
            $query->where('is_read', false);
        } elseif ($request->input('status') === 'read') {
            $query->where('is_read', true);
        } elseif ($request->input('status') === 'replied') {
            $query->whereNotNull('replied_at');
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(10);

        $totalCount = ContactMessage::count();
        $unreadCount = ContactMessage::where('is_read', false)->count();
        $readCount = ContactMessage::where('is_read', true)->count();
        $repliedCount = ContactMessage::whereNotNull('replied_at')->count();

        return view('admin.contact.index', compact('messages', 'totalCount', 'unreadCount', 'readCount', 'repliedCount'));
    }

    public function markAllAsRead()
    {
        ContactMessage::where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'Semua pesan baru telah ditandai sebagai sudah dibaca.');
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);

        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.contact.show', compact('message'));
    }

    public function toggleRead($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['is_read' => !$message->is_read]);

        $statusText = $message->is_read ? 'ditandai sudah dibaca' : 'ditandai belum dibaca';
        return back()->with('success', "Pesan dari {$message->name} berhasil {$statusText}.");
    }

    public function reply(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['replied_at' => now()]);

        return back()->with('success', "Status balasan untuk pesan {$message->name} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.contact.index')->with('success', 'Pesan kontak berhasil dihapus.');
    }
}
