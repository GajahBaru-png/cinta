import { Head } from '@inertiajs/react';
import Login from './auth/login';

export default function Welcome() {
    return (
        <>
            <Head title="Welcome" />
            <div className="flex min-h-screen bg-gray-100">
                {/* LEFT PANEL - Login */}
                <div className="w-full lg:w-1/2 bg-white flex flex-col justify-center items-center px-8 py-10 
                                rounded-3xl shadow-md">
                    <div className="w-full max-w-md">
                        <div className="mb-8">
                            <h1 className="text-3xl font-bold text-[#1b1b18] mb-2">Selamat Datang! di PKL Stembayo</h1>
                            <p className="text-muted-foreground">
                                Login dengan email dan password untuk melanjutkan.
                            </p>
                        </div>
                        <Login canResetPassword={true} />
                    </div>
                </div>

                {/* RIGHT PANEL - Info Section */}
                <div className="hidden lg:flex w-1/2 flex-col justify-center bg-pink-600 text-white px-16 py-12 
                                 shadow-md">
                    <div className="max-w-md space-y-10">
                        <div>
                            <h2 className="text-xl font-semibold mb-2 flex items-center gap-2">
                                <span className="inline-block bg-white/20 p-1 rounded">📅</span>
                                PKL
                            </h2>
                            <p className="text-sm leading-relaxed">
                                Kelola data PKL, mulai dari pendaftaran hingga laporan kegiatan PKL siswa.
                            </p>
                        </div>
                        <div>
                            <h2 className="text-xl font-semibold mb-2 flex items-center gap-2">
                                <span className="inline-block bg-white/20 p-1 rounded">👤</span>
                                Daftar Siswa dan Guru
                            </h2>
                            <p className="text-sm leading-relaxed">
                                Kamu bisa melihat daftar siswa dan guru untuk mempermudah dirimu.
                            </p>
                        </div>
                        <div>
                            <h2 className="text-xl font-semibold mb-2 flex items-center gap-2">
                                <span className="inline-block bg-white/20 p-1 rounded">📊</span>
                                Dashboard
                            </h2>
                            <p className="text-sm leading-relaxed">
                                Kamu bisa melihat laporan PKL Kamu.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
