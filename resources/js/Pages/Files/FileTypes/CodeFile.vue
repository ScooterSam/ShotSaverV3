<template>

	<div>
		<div v-if="contents">
			<pre><code class="code-container">{{ contents }}</code></pre>
		</div>
		<div v-else class="flex justify-center items-center p-12 bg-smoke">
			<Spinner/>
		</div>
	</div>
</template>

<script>


import Spinner from '@/Pages/Files/Spinner';
export default {
	props      : ['file', 'contents'],
	name       : "CodeFile",
	components : {Spinner},
	mounted()
	{
		document.querySelectorAll('.code-container').forEach(block => {
			hljs.highlightBlock(block);
			hljs.lineNumbersBlock(block);
		});

	},
	data()
	{
		return {};
	}
};
</script>

<style scoped>

</style>
