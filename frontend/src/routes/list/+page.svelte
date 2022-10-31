<script lang="ts">
    import {onMount} from "svelte";
    import {get} from "$lib/openapi";
    import {type Book} from "$lib/openapi/model";

    let books: Array<Book> = [];

    onMount(() => {
        get().apiBooksGetCollection().then(res => books = res.data['hydra:member']);
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
