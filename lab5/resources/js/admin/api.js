export async function requestJson(url, signal) {
    const response = await fetch(url, {
        headers: { Accept: 'application/json' },
        signal,
    });
    if (!response.ok) {
        throw new Error(`Сервер повернув статус ${response.status}.`);
    }
    return response.json();
}
