<template>
	<div class="">
		<video id="video"
		       width="1920"
		       controls
		       playsinline
		       height="1080">
			<source :type="file.mime_type" :src="file.url" />
		</video>
	</div>
</template>

<script>

import Plyr from 'plyr';

import 'plyr/dist/plyr.css';


export default {
	props   : ['file'],
	name    : "VideoFile",
	data()
	{
		return {};
	},
	mounted()
	{

		const player = new Plyr('#video', {
			captions   : {
				active : false,
			},
			clickToPlay : true,
			resetOnEnd : true
		});

	},
	methods : {}
};
</script>

<style scoped>

</style>
