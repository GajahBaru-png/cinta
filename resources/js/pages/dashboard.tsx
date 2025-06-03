import React from 'react';
import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import { type BreadcrumbItem } from '@/types';
import {
    User as UserIcon,
    Hash,
    Users,
    Home,
    Phone,
    Mail,
    Briefcase,
    AlertTriangle,
    Award,
    CalendarDays,
    School,
    Factory,
    CalendarCheck,
} from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
];

type User = {
    name: string;
    email: string;
};

type DashboardProps = {
    user: User;
    siswa: {
        nama: string;
        nis: string;
        gender: string;
        alamat: string;
        kontak: string;
        email: string;
    } | null;
    status_pkl: boolean | null;
    pkl: {
        mulai: string;
        selesai: string;
        guru: {
            nama: string;
        } | null;
        industri: {
            nama: string;
            alamat: string;
        } | null;
    } | null;
};

export default function Dashboard({ user, siswa, status_pkl, pkl }: DashboardProps) {
    const InfoItem = ({ icon, label, value }: {
        icon: React.ReactNode;
        label: string;
        value: string | React.ReactNode;
    }) => (
        <div className="flex items-start py-3">
            <span className="mr-3 mt-1 text-pink-500">{icon}</span>
            <div className="flex flex-col">
                <span className="text-xs text-slate-400 uppercase tracking-wider">{label}</span>
                <span className="text-slate-100 text-md">{value}</span>
            </div>
        </div>
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="p-4 md:p-6 bg-black min-h-screen text-slate-100">

                {/* Welcome Banner */}
                <div className="mb-8 flex items-center space-x-4 p-6 bg-gradient-to-r from-pink-600 to-purple-600 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <Award size={48} className="text-white opacity-80" />
                    <div>
                        <h1 className="text-white text-2xl md:text-3xl font-bold">
                            Selamat Datang, {user.name}!
                        </h1>
                        <p className="text-purple-200 text-sm md:text-md">
                            Senang melihat Anda kembali di dashboard.
                        </p>
                    </div>
                </div>

                {/* Kartu Informasi Siswa */}
                <div className="bg-white/10 shadow-2xl rounded-xl p-6 md:p-8">
                    <h2 className="text-xl md:text-2xl font-semibold mb-6 text-pink-500 border-b border-slate-700 pb-3">
                        Dashboard Siswa
                    </h2>

                    {siswa ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2">
                            <InfoItem icon={<UserIcon size={20} />} label="Nama" value={siswa.nama} />
                            <InfoItem icon={<Hash size={20} />} label="NIS" value={siswa.nis} />
                            <InfoItem icon={<Users size={20} />} label="Gender" value={siswa.gender === 'L' ? 'Laki-Laki' : 'Perempuan'} />
                            <InfoItem icon={<Home size={20} />} label="Alamat" value={siswa.alamat} />
                            <InfoItem icon={<Phone size={20} />} label="Kontak" value={siswa.kontak} />
                            <InfoItem icon={<Mail size={20} />} label="Email Siswa" value={siswa.email} />
                            <InfoItem 
                                icon={<Briefcase size={20} />} 
                                label="Status PKL" 
                                value={
                                    <span className={`inline-block px-3 py-1 my-2 text-xs font-semibold rounded-full transition-all duration-300
                                        ${status_pkl 
                                            ? 'bg-green-200 text-green-800 hover:bg-green-300' 
                                            : 'bg-red-200 text-red-800 hover:bg-red-300'}`}>
                                        {status_pkl ? 'Sudah PKL' : 'Belum PKL'}
                                    </span>
                                }
                            />
                        </div>
                    ) : (
                        <div className="flex flex-col items-center justify-center text-center p-10 bg-slate-700/50 rounded-lg">
                            <AlertTriangle size={48} className="text-yellow-500 mb-4" />
                            <p className="text-yellow-400 text-lg font-semibold">Data siswa tidak ditemukan.</p>
                            <p className="text-slate-400 text-sm">
                                Pastikan data siswa sudah terhubung dengan akun Anda.
                            </p>
                        </div>
                    )}

                    {/* Detail PKL */}
                    {pkl && (
                        <div className="mt-6 bg-white/10 inset-shadow-2xs p-4 rounded-xl">
                            <h3 className="text-lg font-semibold text-pink-400 mb-3 flex items-center gap-2">
                                <Briefcase size={20} className="text-pink-400" />
                                Detail PKL
                            </h3>
                            <div className="space-y-2 text-slate-100">
                                <p className="flex items-center gap-2">
                                    <School size={18} className="text-pink-300" />
                                    <strong>Pembimbing:</strong> {pkl.guru?.nama || '–'}
                                </p>
                                <p className="flex items-center gap-2">
                                    <Factory size={18} className="text-pink-300" />
                                    <strong>Industri:</strong> {pkl.industri?.nama || '–'}
                                </p>
                                <p className="flex items-center gap-2">
                                    <Home size={18} className="text-pink-300" />
                                    <strong>Alamat Industri:</strong> {pkl.industri?.alamat || '–'}
                                </p>
                                <p className="flex items-center gap-2">
                                    <CalendarDays size={18} className="text-pink-300" />
                                    <strong>Mulai:</strong>{' '}
                                    {new Date(pkl.mulai).toLocaleDateString('id-ID', {
                                        day: '2-digit',
                                        month: 'long',
                                        year: 'numeric',
                                    })}
                                </p>
                                <p className="flex items-center gap-2">
                                    <CalendarCheck size={18} className="text-pink-300" />
                                    <strong>Selesai:</strong>{' '}
                                    {new Date(pkl.selesai).toLocaleDateString('id-ID', {
                                        day: '2-digit',
                                        month: 'long',
                                        year: 'numeric',
                                    })}
                                </p>
                            </div>
                        </div>
                    )}

                </div>
            </div>
        </AppLayout>
    );
}
