import React from 'react';
import { useForm, Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout'; // Pastikan path ini benar
import { type BreadcrumbItem } from '@/types';
import { PageProps } from '@/types'; // PageProps biasanya sudah menyertakan 'errors'

// Breadcrumbs untuk AppLayout
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Laporan', href: '/laporan' },
    { title: 'Tambah', href: '/laporan.create' }
];

// Definisikan tipe untuk model yang akan diterima dari backend
// Pastikan nama properti sesuai dengan kolom di database Anda
interface Siswa {
    id: number;
    nama: string;
}

interface Guru {
    id: number;
    nama: string;
}

interface Industri {
    id: number;
    nama: string;
}


// Definisikan tipe untuk props halaman yang akan diterima dari Laravel
interface LaporanProps extends PageProps {
    siswas: Siswa[];
    gurus: Guru[];
    industris: Industri[];
    userRole: string;
    user: User;
    flash: {
        success?: string;
        error?: string;
    };
    // 'errors' sudah termasuk dalam PageProps dari Inertia
}

type User = {
    name: string;
    email: string;
};

// Komponen utama Laporan
export default function LaporanCreate({ siswas, gurus, industris, errors, userRole, user, flash }: LaporanProps) {
    const { data, setData, post, processing, reset, recentlySuccessful } = useForm({
        siswa_id: siswas.length === 1 ? String(siswas[0].id) : '',
        guru_id: '',
        industri_id: '',
        mulai: '',
        selesai: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('laporan.store'), {
            onSuccess: () => {
                reset();
            },
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Laporan PKL" />
            
            <div className="p-6 bg-black min-h-screen text-white">  
                {flash.success && (
                            <div className="mb-4 p-3 bg-green-500 text-white rounded-lg shadow-md animate-pulse">
                                {flash.success}
                            </div>
                        )}
                        {flash.error && (
                            <div className="mb-4 p-3 bg-red-500 text-white rounded-lg shadow-md animate-pulse">
                                {flash.error}
                            </div>
                        )}
                {userRole !== 'guru' && (
                    <>  
                        
                        <h1 className="text-2xl mb-6 text-pink-600 hover:text-pink-500">Tambah Laporan PKL</h1>

                        <form onSubmit={handleSubmit} className="space-y-4 max-w-lg bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow-lg text-white transition">
                            {/* Siswa */}
                            <div>
                                <label className="block text-sm font-semibold text-white mb-2">Siswa</label>
                                <p className="w-full rounded p-2 text-center text-white border bg-pink-600">
                                    {siswas.find((s) => s.id === Number(data.siswa_id))?.nama}
                                </p>
                            </div>

                            {/* Guru */}
                            <div>
                                <label className="block text-sm font-semibold text-white mb-2">Guru Pembimbing</label>
                                <select
                                    value={data.guru_id}
                                    onChange={(e) => setData('guru_id', e.target.value)}
                                    className="w-full rounded p-2 text-black border bg-white/20 "
                                    required
                                >
                                    <option value="">Pilih Guru</option>
                                    {gurus.map((guru) => (
                                        <option key={guru.id} value={guru.id}>{guru.nama}</option>
                                    ))}
                                </select>
                                {errors.guru_id && <div className="text-red-500 text-sm">{errors.guru_id}</div>}
                            </div>

                            {/* Industri */}
                            <div>
                                <label className="block text-sm font-semibold text-white mb-2">Industri</label>
                                <select
                                    value={data.industri_id}
                                    onChange={(e) => setData('industri_id', e.target.value)}
                                    className="w-full rounded p-2 text-black border bg-white/20 "
                                    required
                                >
                                    <option value="">Pilih Industri</option>
                                    {industris.map((industri) => (
                                        <option key={industri.id} value={industri.id}>{industri.nama}</option>
                                    ))}
                                </select>
                                {errors.industri_id && <div className="text-red-500 text-sm">{errors.industri_id}</div>}
                            </div>

                            {/* Tanggal Mulai */}
                            <div>
                                <label className="block text-sm font-semibold text-white mb-2">Tanggal Mulai</label>
                                <input
                                    type="date"
                                    value={data.mulai}
                                    onChange={(e) => setData('mulai', e.target.value)}
                                    className="w-full rounded p-2 text-black border bg-white/20 "
                                    required
                                />
                                {errors.mulai && <div className="text-red-500 text-sm">{errors.mulai}</div>}
                            </div>

                            {/* Tanggal Selesai */}
                            <div>
                                <label className="block text-sm font-semibold text-white mb-2">Tanggal Selesai</label>
                                <input
                                    type="date"
                                    value={data.selesai}
                                    onChange={(e) => setData('selesai', e.target.value)}
                                    className="w-full rounded p-2 text-black border bg-white/20 "
                                    required
                                />
                                {errors.selesai && <div className="text-red-500 text-sm">{errors.selesai}</div>}
                            </div>

                            {/* Tombol */}
                            <button
                                type="submit"
                                disabled={processing}
                                className="bg-pink-600 hover:bg-pink-700 rounded px-4 py-2 mt-4"
                            >
                                {processing ? 'Menyimpan...' : 'Simpan'}
                            </button>

                            {/* Sukses */}

                        </form>
                    </>
                )}
                {userRole === 'siswa' &&(
                    <div className="flex items-center justify-center min-h-screen w-full text-center">
                        <h1 className="text-xl sm:text-6xl md:text-8xl font-bold text-gray-400">
                            Maaf, {user.name} Kamu bukan Siswa
                        </h1>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}