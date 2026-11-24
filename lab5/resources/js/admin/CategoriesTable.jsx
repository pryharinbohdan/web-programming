import { useEffect, useState } from 'react';
import { requestJson } from './api';
import RequestMessage from './RequestMessage';

function CategoryRow({ category }) {
    return (
        <tr className="border-b border-admin-line transition-colors last:border-b-0 hover:bg-admin-canvas">
            <td className="w-16 px-4 py-3 font-mono text-xs tabular-nums text-admin-muted">
                {category.id}
            </td>
            <td className="px-4 py-3 font-medium text-admin-ink">{category.name}</td>
            <td className="px-4 py-3 font-mono text-xs text-admin-muted">
                <code>{category.slug}</code>
            </td>
        </tr>
    );
}

export default function CategoriesTable() {
    const [categories, setCategories] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [reloadKey, setReloadKey] = useState(0);

    useEffect(() => {
        const controller = new AbortController();

        async function loadCategories() {
            setLoading(true);
            setError('');

            try {
                const payload = await requestJson('/api/categories', controller.signal);

                if (controller.signal.aborted) {
                    return;
                }

                setCategories(Array.isArray(payload.data) ? payload.data : []);
            } catch (err) {
                if (controller.signal.aborted || err.name === 'AbortError') {
                    return;
                }
                setError(err.message || 'Не вдалося завантажити категорії.');
            } finally {
                if (!controller.signal.aborted) {
                    setLoading(false);
                }
            }
        }

        loadCategories();

        return () => controller.abort();
    }, [reloadKey]);

    if (loading) {
        return <RequestMessage status="loading" message="Завантаження…" />;
    }

    if (error) {
        return (
            <RequestMessage
                status="error"
                message={error}
                onRetry={() => setReloadKey((prev) => prev + 1)}
            />
        );
    }

    if (categories.length === 0) {
        return <RequestMessage status="empty" message="Категорій поки немає." />;
    }

    return (
        <section className="overflow-hidden rounded-2xl border border-admin-line bg-admin-surface">
            <div className="flex flex-wrap items-center justify-between gap-2 border-b border-admin-line px-5 py-4">
                <h2 className="text-xl font-normal text-admin-ink">Список категорій</h2>
                <span className="rounded-full bg-admin-track px-3 py-1 text-xs font-bold text-admin-ink">
                    {categories.length} категорій
                </span>
            </div>

            <div className="overflow-x-auto">
                <table className="w-full min-w-[360px] border-collapse text-left text-sm">
                    <thead className="bg-admin-track text-xs uppercase tracking-wide text-admin-muted">
                        <tr>
                            <th scope="col" className="px-4 py-3 font-bold">ID</th>
                            <th scope="col" className="px-4 py-3 font-bold">Назва</th>
                            <th scope="col" className="px-4 py-3 font-bold">Slug</th>
                        </tr>
                    </thead>
                    <tbody>
                        {categories.map((category) => (
                            <CategoryRow key={category.id} category={category} />
                        ))}
                    </tbody>
                </table>
            </div>
        </section>
    );
}
