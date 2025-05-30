export function get(url) {
    return fetch(url).then(res => res.json());
}

export function post(url, data) {
    return fetch(url, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    }).then(res => res.json());
}