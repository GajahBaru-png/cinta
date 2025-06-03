import { useEffect, useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import { User, Mail, Phone, MapPin } from 'lucide-react';
import axios from 'axios';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Guru Pembimbing', href: '/guru' },
];

type Guru = {
  nama: string;
  nip: string;
  alamat: string;
  kontak: string;
  email: string;
};

export default function GuruPage() {
  const [gurus, setGurus] = useState<Guru[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    axios
      .get('/api/guru')
      .then((res) => {
        setGurus(res.data);
        setLoading(false);
      })
      .catch((err) => {
        console.error(err);
        setError('Gagal memuat data guru.');
        setLoading(false);
      });
  }, []);

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Guru" />
      <div className="p-6 bg-black min-h-screen">
        {loading ? (
          <p className="text-white text-center">Memuat data guru...</p>
        ) : error ? (
          <p className="text-red-500 text-center">{error}</p>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {gurus.length === 0 ? (
              <p className="text-gray-400 col-span-full text-center">Tidak ada data guru.</p>
            ) : (
              gurus.map((guru) => (
                <div
                  key={guru.nip}
                  className="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow-lg text-white transition hover:scale-[1.02]"
                >
                  <h3 className="text-xl font-bold mb-3 hover:text-pink-500 text-pink-600 flex items-center gap-2">
                    <User className="w-5 h-5" />
                    {guru.nama}
                  </h3>
                  <p className="flex items-center text-sm text-gray-300 mb-1 gap-2">
                    <User className="w-4 h-4" />
                    NIP: {guru.nip}
                  </p>
                  <p className="flex items-center text-sm text-gray-400 mb-1 gap-2">
                    <Mail className="w-4 h-4" />
                    Email: {guru.email}
                  </p>
                  <p className="flex items-center text-sm text-gray-400 mb-1 gap-2">
                    <Phone className="w-4 h-4" />
                    Kontak: {guru.kontak}
                  </p>
                  <p className="flex items-center text-sm text-gray-400 gap-2">
                    <MapPin className="w-4 h-4" />
                    Alamat: {guru.alamat}
                  </p>
                </div>
              ))
            )}
          </div>
        )}
      </div>
    </AppLayout>
  );
}
