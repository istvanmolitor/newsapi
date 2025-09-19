import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';


type Article = {
    id: number;
    title: string;
}

type Keyword = {
    id: number;
    keyword: string;
}

type Paginator<T> = {
    data: T[];
}

type Props = {
    keyword: Keyword;
    articles: Paginator<Article>;
    title?: string;
}

export default function Show({ keyword, articles, title }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Kulcsszavak', href: '/keywords' },
        { title: keyword.keyword, href: `/keywords/${keyword.id}` },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title ?? `Kulcsszó: ${keyword.keyword}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-semibold">Kulcsszó: {keyword.keyword}</h1>
                <ul className="space-y-2">
                    {articles.data.length === 0 && (
                        <li>Nincs találat ehhez a kulcsszóhoz.</li>
                    )}
                    {articles.data.map(a => (
                        <li key={a.id}>
                            <Link href={`/article/${a.id}`} className="text-blue-600 hover:underline">
                                {a.title}
                            </Link>
                        </li>
                    ))}
                </ul>
            </div>
        </AppLayout>
    );
}
