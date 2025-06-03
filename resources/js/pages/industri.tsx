import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router } from '@inertiajs/react';
import { User, MapPin, Briefcase, Globe, Phone } from 'lucide-react';
import { useEffect,useState  } from 'react';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Industri',
        href: '/industri',
    },
];



type Industri = {
    id: number;
    nama: string;
    alamat: string;
    kontak: string;
    guru_pembimbing: string;
    bidang_usaha: string;
    website: string;
    // tambahkan field lain sesuai kebutuhan
};

type Props = {
    industri: {
        data: Industri[];
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
    filters: {
        search: string;
    };
    userRole: string;
};



export default function IndustriPage({ industri, filters,userRole }: Props) {
    const [search, setSearch] = useState(filters.search || '');

    useEffect(() => {
        const handler = setTimeout(() => {
            router.get(
                route('industri.index'),
                { search },
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                    only: ['industri'],
                }
            );
        }, 300); // debounce 300ms

        return () => clearTimeout(handler);
    }, [search]);
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
                    <Head title="Industri" />
                        <div className="p-6 bg-black min-h-screen">
                            <div className="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between sm:space-x-4 space-y-4 sm:space-y-0">
                            {/* Tombol Tambah Industri */}
                            {userRole !== 'guru' && (
                                <a
                                    href="/industri/create"
                                    className="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded whitespace-nowrap"
                                >
                                    Tambah Industri
                                </a>
                            )}

                            {/* Pagination */}
                            <div className="flex flex-wrap justify-center sm:justify-start gap-1">
                                {industri.links.map((link, index) => (
                                    <button
                                        key={index}
                                        disabled={!link.url}
                                        onClick={() => link.url && router.visit(link.url, { preserveScroll: true })}
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                        className={`px-3 py-1 border text-sm rounded-md transition ${
                                            link.active
                                                ? 'bg-pink-600 text-white font-bold'
                                                : 'bg-white/20 text-white hover:bg-white/30'
                                        } ${!link.url && 'opacity-50 cursor-not-allowed'}`}
                                    />
                                ))}
                            </div>

                            {/* Input Pencarian */}
                            <input
                                type="text"
                                placeholder="Cari industri..."
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="p-2 border rounded w-full sm:max-w-xs"
                            />
                        </div>
                            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                {industri.data.map((item) => (
                                    <div
                                        key={item.id}
                                        className="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow-lg text-white transition hover:scale-[1.02]"
                                    >
                                        <h3 className="text-xl font-bold mb-2 hover:text-pink-500 text-pink-600">{item.nama  || '-'}</h3>

                                        <div className="flex items-center text-sm text-gray-300 mb-1">
                                            <Briefcase className="w-4 h-4 mr-2" />
                                            {item.bidang_usaha  || '-'}
                                        </div>

                                        <div className="flex items-center text-sm text-gray-400 mb-1">
                                            <MapPin className="w-4 h-4 mr-2" />
                                            {item.alamat  || '-'}
                                        </div>
                                        <div className="flex items-center text-sm text-gray-400 mb-1">
                                            <Phone className="w-4 h-4 mr-2" />
                                            {item.kontak  || '-'}
                                        </div>
                                        <div className="flex items-center text-sm text-gray-400 mb-1">
                                            <Globe className="w-4 h-4 mr-2" />
                                            {item.website || '-'}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
        </AppLayout>
    );
}