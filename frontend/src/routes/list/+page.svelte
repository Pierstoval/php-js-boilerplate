<script lang="ts">
    import {onMount} from "svelte";
    import type { Book } from '$lib/openapi/schema';
    import {get} from "$lib/openapi";

    let books: Array<Book> = [];

    onMount(async () => {
        const res = await get().apiBooksGetCollection();
        books = res.data['hydra:member'];
    });
</script>

<h1>Available books</h1>

<table>
    <tr>
        <th>#</th>
        <th>Title</th>
        <th>Actions</th>
    </tr>
    {#each books as book (book.id)}
        <tr>
            <td>{book.id}</td>
            <td>{book.title}</td>
            <td>
                ...
            </td>
        </tr>
    {/each}
</table>
