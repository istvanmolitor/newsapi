import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Kulcsszavak', href: '/keywords' },
];

type Keyword = {
    id: number;
    keyword: string;
}

type Paginator<T> = {
    data: T[];
}

type Props = {
    keywords: Paginator<Keyword>;
    title?: string;
}

export default function Index({ keywords, title = 'Kulcsszavak' }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-semibold">{title}</h1>
                <ul className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                    {keywords.data.map(k => (
                        <li key={k.id} className="">
                            <Link href={`/keywords/${k.id}`} className="text-blue-600 hover:underline">
                                {k.keyword}
                            </Link>
                        </li>
                    ))}
                </ul>
            </div>
        </AppLayout>
    );
}
