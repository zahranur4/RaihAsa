<?php

namespace App\Http\Controllers\Panti;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        // View composer already provides $currentPanti, but keep this method for route clarity
        return view('panti.profil.index');
    }

    public function update(Request $request)
    {
        $panti = DB::table('panti_asuhan')->where('user_id', Auth::id())->first();

        $rules = [
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:orphanage,elderly,foundation',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'kode_pos' => 'nullable|string|max:10',
            'kapasitas' => 'nullable|integer|min:0',
            'nomor_legalitas' => 'nullable|string|max:255',
            'tanggal_berdiri' => 'nullable|date',
            'nama_penanggung_jawab' => 'nullable|string|max:255',
            'posisi_penanggung_jawab' => 'nullable|string|max:255',
            'nik' => ($panti ? 'nullable|digits:16' : 'required|digits:16'),
            'email' => 'nullable|email|max:255',
            // Additional legal fields and uploads
            'nama_notaris' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'doc_akte' => 'nullable|file|mimes:pdf|max:5120',
            'doc_sk' => 'nullable|file|mimes:pdf|max:5120',
            'doc_npwp' => 'nullable|file|mimes:pdf|max:5120',
            'doc_other' => 'nullable|file|mimes:pdf|max:5120',
        ];

        $data = $request->validate($rules);

        // Update user's email if provided
        if (!empty($data['email'])) {
            $user = Auth::user();
            if ($user && $user->email !== $data['email']) {
                $user->email = $data['email'];
                $user->save();
            }
        }

        $values = [
            'nama' => $data['nama'] ?? ($panti->nama ?? null),
            'jenis' => $data['jenis'] ?? ($panti->jenis ?? null),
            'nomor_telepon' => $data['nomor_telepon'] ?? ($panti->nomor_telepon ?? null),
            'alamat' => $data['alamat'] ?? ($panti->alamat ?? null),
            'kode_pos' => $data['kode_pos'] ?? ($panti->kode_pos ?? null),
            'kapasitas' => $data['kapasitas'] ?? ($panti->kapasitas ?? null),
            'nomor_legalitas' => $data['nomor_legalitas'] ?? ($panti->nomor_legalitas ?? null),
            'tanggal_berdiri' => $data['tanggal_berdiri'] ?? ($panti->tanggal_berdiri ?? null),
            'nama_penanggung_jawab' => $data['nama_penanggung_jawab'] ?? ($panti->nama_penanggung_jawab ?? null),
            'posisi_penanggung_jawab' => $data['posisi_penanggung_jawab'] ?? ($panti->posisi_penanggung_jawab ?? null),
            'nik' => $data['nik'] ?? ($panti->nik ?? null),
            'nama_notaris' => $data['nama_notaris'] ?? ($panti->nama_notaris ?? null),
            'deskripsi' => $data['deskripsi'] ?? ($panti->deskripsi ?? null),
            'updated_at' => now(),
        ];

        // Handle uploaded documents
        $userId = Auth::id();
        foreach (['doc_akte', 'doc_sk', 'doc_npwp', 'doc_other'] as $docField) {
            if ($request->hasFile($docField)) {
                $file = $request->file($docField);
                $path = $file->store("panti_docs/{$userId}", 'public');

                // delete previous file if exists
                if ($panti && !empty($panti->{$docField})) {
                    Storage::disk('public')->delete($panti->{$docField});
                }

                $values[$docField] = $path;
            }
        }

        if ($panti) {
            DB::table('panti_asuhan')->where('user_id', Auth::id())->update($values);
        } else {
            DB::table('panti_asuhan')->insert(array_merge($values, ['user_id' => Auth::id(), 'created_at' => now()]));
        }

        return redirect()->route('panti.profil')->with('success', 'Profil berhasil diperbarui.');
    }
}
