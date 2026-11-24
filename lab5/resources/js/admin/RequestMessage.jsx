export default function RequestMessage({ status = 'info', message, onRetry }) {
    const isError = status === 'error';

    return (
        <div
            role={isError ? 'alert' : 'status'}
            className="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-admin-line bg-admin-surface px-5 py-4 text-sm text-admin-muted"
        >
            <p className={isError ? 'font-medium text-red-700' : ''}>{message}</p>
            {isError && onRetry && (
                <button
                    type="button"
                    onClick={onRetry}
                    className="cursor-pointer rounded-lg bg-admin-accent px-3.5 py-2 text-xs font-bold text-admin-on-accent transition hover:opacity-90"
                >
                    Спробувати ще раз
                </button>
            )}
        </div>
    );
}
