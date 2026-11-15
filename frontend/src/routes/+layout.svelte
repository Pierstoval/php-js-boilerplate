<script lang="ts">
	import '../app.scss';
	import favicon from '#lib/assets/favicon.svg';
	import type { LayoutProps } from './$types';
	import TopMenu from '../lib/components/TopMenu.svelte';

	let { children }: LayoutProps = $props();
</script>

<svelte:head>
	<link rel="icon" href={favicon} />
</svelte:head>

<main>
  <TopMenu />

  {@render children()}
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
