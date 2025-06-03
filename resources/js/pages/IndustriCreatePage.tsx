import { useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';

export default function IndustriCreatePage() {
    const { data, setData, post, processing, errors } = useForm({
        nama: '',
        alamat: '',
        kontak: '',
        bidang_usaha: '',
        website: '',
        email: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post(route('industri.store'));
    }

    return (
        <AppLayout breadcrumbs={[{ title: 'Industri', href: '/industri' }, { title: 'Tambah', href: '/industri/create' }]}>
            <Head title="Tambah Industri" />
            <div className="p-6 bg-black min-h-screen text-white">
                <h1 className="text-2xl mb-6 text-pink-600 hover:text-pink-500">Tambah Data Industri</h1>

                <form onSubmit={submit} className="space-y-4 max-w-lg bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow-lg text-white transition">
                    <div>
                        <label className="block mb-1">Nama</label>
                        <input
                            type="text"
                            value={data.nama}
                            onChange={e => setData('nama', e.target.value)}
                            className="w-full rounded p-2 text-white border bg-white/20"
                            required
                        />
                        {errors.nama && <div className="text-red-500 text-sm">{errors.nama}</div>}
                    </div>

                    <div>
                        <label className="block mb-1">Alamat</label>
                        <input
                            type="text"
                            value={data.alamat}
                            onChange={e => setData('alamat', e.target.value)}
                            className="w-full rounded p-2 text-white border bg-white/20"
                        />
                    </div>

                    <div>
                        <label className="block mb-1">Kontak</label>
                        <input
                            type="text"
                            value={data.kontak}
                            onChange={e => setData('kontak', e.target.value)}
                            className="w-full rounded p-2 text-white border bg-white/20"
                        />
                    </div>

                    <div>
                        <label className="block mb-1">Bidang Usaha</label>
                        <input
                            type="text"
                            value={data.bidang_usaha}
                            onChange={e => setData('bidang_usaha', e.target.value)}
                            className="w-full rounded p-2 text-white border bg-white/20"
                        />
                    </div>

                    <div>
                        <label className="block mb-1">Website</label>
                        <input
                            type="url"
                            value={data.website}
                            onChange={e => setData('website', e.target.value)}
                            className="w-full rounded p-2 text-white border bg-white/20"
                        />
                    </div>
                    <div>
                        <label className="block mb-1">Email</label>
                        <input
                            type="email"
                            value={data.email}
                            onChange={e => setData('email', e.target.value)}
                            className="w-full rounded p-2 text-white border bg-white/20"
                        />
                    </div>

                    <button
                        type="submit"
                        disabled={processing}
                        className="bg-pink-600 hover:bg-pink-700 rounded px-4 py-2 mt-4 "
                    >
                        Simpan
                    </button>
                </form>
            </div>
        </AppLayout>
    );
}