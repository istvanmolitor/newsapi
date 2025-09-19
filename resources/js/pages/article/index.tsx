import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

type Article = {
    id: number;
    title: string;
}

type Paginator<T> = {
    data: T[];
}

type Props = {
    articles: Paginator<Article>;
    title?: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Hírek', href: '/' },
];

export default function Index({ articles, title = 'Hírek' }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-semibold">{title}</h1>
                <ul className="space-y-2">
                    {articles.data.map(a => (
                        <li key={a.id}>
                            <Link href={`/article/${a.id}`} className="text-blue-600 hover:underline">{a.title}</Link>
                        </li>
                    ))}
                </ul>
            </div>
        </AppLayout>
    );
}
