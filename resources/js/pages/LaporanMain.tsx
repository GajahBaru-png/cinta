import React from 'react';
import { useForm, Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout'; // Pastikan path ini benar
import { type BreadcrumbItem } from '@/types';
import { PageProps } from '@/types'; // PageProps biasanya sudah menyertakan 'errors'


// Definisikan tipe data untuk Laporan yang diterima dari controller
interface Laporan {
    id: number;
    mulai: string;
    selesai: string;
    siswa: { nama: string };
    guru: { nama: string };
    industri: { nama: string };
    durasi: string;
}

type User = {
    name: string;
    email: string;
};

// Props halaman
interface LaporanMainProps extends PageProps {
    userRole: string;
    laporans: {
        data: Laporan[];
        current_page: number;
        last_page: number;
        next_page_url: string | null;
        prev_page_url: string | null;
        links: {
            url: string | null;
            label: string;
            active: boolean;
        }[];
    };
    flash: {
        success?: string;
        error?: string;
    };
}

export default function LaporanMain({ laporans, flash, userRole }: LaporanMainProps) {
    // Tentukan userRole, misal dari user.role jika tersedia, atau set default
    //  // Ganti sesuai struktur user Anda

    // ✅ Perbaikan breadcrumbs agar sesuai tipe
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Laporan PKL', href: route('laporan.index') },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Daftar Laporan PKL" />

            <div className="p-6 bg-black min-h-screen text-white">
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between sm:space-x-4 space-y-4 sm:space-y-0 mb-6">
                {/* Judul */}
                    <h1 className="text-2xl text-pink-600 font-bold whitespace-nowrap">Daftar Laporan PKL</h1>

                    {/* Pagination */}
                    <div className="flex flex-wrap justify-center sm:justify-start gap-1">
                        {laporans.links.map((link, index) => (
                            <Link
                                key={index}
                                href={link.url || '#'}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                                className={`px-4 py-2 border rounded-md text-sm transition ${
                                    link.active
                                        ? 'bg-pink-600 text-white font-bold'
                                        : 'bg-white/20 text-white hover:bg-white/30'
                                } ${!link.url && 'pointer-events-none opacity-50'}`}
                            />
                        ))}
                    </div>

                    {/* Tombol Tambah */}
                    {userRole !== 'guru' && (
                        <Link 
                            href={route('laporan.create')}
                            className="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded whitespace-nowrap transition"
                        >
                            Tambah Laporan
                        </Link>
                    )}
                </div>

                {/* Flash Message */}
                {flash?.success && (
                    <div className="mb-4 p-3 bg-green-500 text-white rounded-lg shadow-md animate-pulse">
                        {flash.success}
                    </div>
                )}
                {flash?.error && (
                    <div className="mb-4 p-3 bg-red-500 text-white rounded-lg shadow-md animate-pulse">
                        {flash.error}
                    </div>
                )}

                {/* Tabel Data */}
                <div className="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl overflow-hidden shadow-lg">
                    <table className="w-full text-left">
                        <thead className="bg-white/20">
                            <tr>
                                <th className="p-4">Nama Siswa</th>
                                <th className="p-4">Guru Pembimbing</th>
                                <th className="p-4">Industri</th>
                                <th className="p-4">Mulai</th>
                                <th className="p-4">Selesai</th>
                                <th className="p-4">Durasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {laporans.data.map((laporan) => (
                                <tr key={laporan.id} className="border-b border-white/10 hover:bg-white/5">
                                    <td className="p-4">{laporan.siswa.nama}</td>
                                    <td className="p-4">{laporan.guru.nama}</td>
                                    <td className="p-4">{laporan.industri.nama}</td>
                                    <td className="p-4">{new Date(laporan.mulai).toLocaleDateString('id-ID')}</td>
                                    <td className="p-4">{new Date(laporan.selesai).toLocaleDateString('id-ID')}</td>
                                    <td className="p-4">{laporan.durasi}</td>
                                </tr>
                            ))}
                            {laporans.data.length === 0 && (
                                <tr>
                                    <td colSpan={5} className="p-4 text-center text-gray-400">
                                        Belum ada data laporan.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
                
            </div>
        </AppLayout>
    );
}
