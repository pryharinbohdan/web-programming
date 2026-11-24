import { useEffect, useState } from 'react';
import { requestJson } from './api';
import RequestMessage from './RequestMessage';

function MetricCard({ label, value, note, primary = false, iconPath }) {
    return (
        <article
            className={`relative overflow-hidden rounded-2xl border border-admin-line p-6 ${
                primary ? 'bg-admin-highlight' : 'bg-admin-surface'
            }`}
        >
            <div className="flex items-start justify-between gap-4">
                <dl>
                    <dt className="text-sm font-bold text-admin-ink">{label}</dt>
                    <dd className="mt-2 text-5xl font-normal tracking-tight tabular-nums text-admin-ink">
                        {value}
                    </dd>
                    <dd className="mt-2 text-xs text-admin-muted">{note}</dd>
                </dl>
                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-admin-line bg-admin-surface text-admin-accent">
                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" className="h-5 w-5">
                        <path
                            d={iconPath}
                            stroke="currentColor"
                            strokeWidth="1.6"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                        />
                    </svg>
                </span>
            </div>
        </article>
    );
}

export default function Dashboard() {
    const [stats, setStats] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [reloadKey, setReloadKey] = useState(0);

    useEffect(() => {
        const controller = new AbortController();

        async function loadMetrics() {
            setLoading(true);
            setError('');

            try {
                const [productsPayload, categoriesPayload] = await Promise.all([
                    requestJson('/api/products', controller.signal),
                    requestJson('/api/categories', controller.signal),
                ]);

                if (controller.signal.aborted) {
                    return;
                }

                setStats({
                    productsTotal: productsPayload.meta?.total ?? 0,
                    categoriesTotal: Array.isArray(categoriesPayload.data)
                        ? categoriesPayload.data.length
                        : 0,
                });
            } catch (err) {
                if (controller.signal.aborted || err.name === 'AbortError') {
                    return;
                }
                setError(err.message || 'Не вдалося завантажити показники.');
            } finally {
                if (!controller.signal.aborted) {
                    setLoading(false);
                }
            }
        }

        loadMetrics();

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

    return (
        <section className="grid grid-cols-1 gap-4 sm:grid-cols-2" aria-label="Показники каталогу">
            <MetricCard
                label="Усього позицій"
                value={stats.productsTotal}
                note="Загальна кількість моделей у каталозі"
                primary
                iconPath="M4 7l8-4 8 4v10l-8 4-8-4zm0 0 8 4 8-4m-8 4v10"
            />
            <MetricCard
                label="Категорій"
                value={stats.categoriesTotal}
                note="Доступні групи комп’ютерної техніки"
                iconPath="M4 7h6l2 2h8v9H4zm0 0V5h6l2 2h8v2"
            />
        </section>
    );
}
