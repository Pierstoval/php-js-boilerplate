import {get} from "../../lib/openapi";

export const csr = false;

/** @type {import('./$types').PageLoad} */
export async function load() {
    const res = await get().apiBooksGetCollection({}, {
        baseURL: 'http://caddy/',
        insecureHTTPParser: true,
        headers: {
            "Host": "localhost",
        },
    });

    // @ts-ignore
    const books = res.data['hydra:member'];

    return {books};
}
