import { useEffect, useState } from 'react';
import { requestJson } from './api';
import RequestMessage from './RequestMessage';

function ProductRow({ product }) {
    const formattedPrice = Number(product.price).toLocaleString('uk-UA', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });

    return (
        <tr className="border-b border-admin-line transition-colors last:border-b-0 hover:bg-admin-canvas">
            <td className="w-16 px-4 py-3 font-mono text-xs tabular-nums text-admin-muted">
                {product.id}
            </td>
            <td className="px-4 py-3 font-medium text-admin-ink">{product.title}</td>
            <td className="px-4 py-3 text-admin-muted">{product.category ?? '—'}</td>
            <td className="px-4 py-3">
                <span className="inline-flex rounded-full bg-admin-track px-2.5 py-0.5 text-xs font-semibold text-admin-ink">
                    {product.operation}
                </span>
            </td>
            <td className="whitespace-nowrap px-4 py-3 text-right font-semibold tabular-nums text-admin-ink">
                {formattedPrice} {product.currency}
            </td>
        </tr>
    );
}

function Pagination({ meta, onPageChange }) {
    if (!meta) {
        return null;
    }

    const currentPage = meta.current_page ?? 1;
    const lastPage = meta.last_page ?? 1;

    return (
        <div className="flex flex-wrap items-center justify-between gap-3 border-t border-admin-line px-5 py-3.5 text-sm text-admin-muted">
            <span>
                Сторінка <strong className="text-admin-ink">{currentPage}</strong> з{' '}
                <strong className="text-admin-ink">{lastPage}</strong> (усього {meta.total ?? 0})
            </span>
            <div className="flex gap-2">
                <button
                    type="button"
                    disabled={currentPage <= 1}
                    onClick={() => onPageChange(currentPage - 1)}
                    className="cursor-pointer rounded-lg border border-admin-line bg-admin-surface px-3 py-1.5 text-xs font-bold text-admin-ink transition hover:bg-admin-track disabled:cursor-default disabled:opacity-45"
                >
                    ← Назад
                </button>
                <button
                    type="button"
                    disabled={currentPage >= lastPage}
                    onClick={() => onPageChange(currentPage + 1)}
                    className="cursor-pointer rounded-lg border border-admin-line bg-admin-surface px-3 py-1.5 text-xs font-bold text-admin-ink transition hover:bg-admin-track disabled:cursor-default disabled:opacity-45"
                >
                    Далі →
                </button>
            </div>
        </div>
    );
}

export default function ProductsTable() {
    const [page, setPage] = useState(1);
    const [products, setProducts] = useState([]);
    const [meta, setMeta] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [reloadKey, setReloadKey] = useState(0);

    useEffect(() => {
        const controller = new AbortController();

        async function loadProducts() {
            setLoading(true);
            setError('');

            try {
                const payload = await requestJson(`/api/products?page=${page}`, controller.signal);

                if (controller.signal.aborted) {
                    return;
                }

                setProducts(Array.isArray(payload.data) ? payload.data : []);
                setMeta(payload.meta ?? null);
            } catch (err) {
                if (controller.signal.aborted || err.name === 'AbortError') {
                    return;
                }
                setError(err.message || 'Не вдалося завантажити позиції каталогу.');
            } finally {
                if (!controller.signal.aborted) {
                    setLoading(false);
                }
            }
        }

        loadProducts();

        return () => controller.abort();
    }, [page, reloadKey]);

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

    if (products.length === 0) {
        return <RequestMessage status="empty" message="Позицій каталогу поки немає." />;
    }

    return (
        <section className="overflow-hidden rounded-2xl border border-admin-line bg-admin-surface">
            <div className="flex flex-wrap items-center justify-between gap-2 border-b border-admin-line px-5 py-4">
                <h2 className="text-xl font-normal text-admin-ink">Список позицій</h2>
                <span className="rounded-full bg-admin-track px-3 py-1 text-xs font-bold text-admin-ink">
                    {meta?.total ?? products.length} позицій
                </span>
            </div>

            <div className="overflow-x-auto">
                <table className="w-full min-w-[660px] border-collapse text-left text-sm">
                    <thead className="bg-admin-track text-xs uppercase tracking-wide text-admin-muted">
                        <tr>
                            <th scope="col" className="px-4 py-3 font-bold">ID</th>
                            <th scope="col" className="px-4 py-3 font-bold">Назва</th>
                            <th scope="col" className="px-4 py-3 font-bold">Категорія</th>
                            <th scope="col" className="px-4 py-3 font-bold">Операція</th>
                            <th scope="col" className="px-4 py-3 text-right font-bold">Ціна</th>
                        </tr>
                    </thead>
                    <tbody>
                        {products.map((product) => (
                            <ProductRow key={product.id} product={product} />
                        ))}
                    </tbody>
                </table>
            </div>

            <Pagination meta={meta} onPageChange={setPage} />
        </section>
    );
}
