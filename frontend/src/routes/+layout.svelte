<script lang="ts">
	import "../app.scss";

	import TopMenu from '../lib/components/TopMenu.svelte';
</script>

<main>
	<TopMenu/>

	<slot></slot>
</main>

<style lang="scss">
	main {
		min-width: 320px;
		max-width: 1080px;
		width: 80%;
		margin-left: auto;
		margin-right: auto;
	}
</style>
