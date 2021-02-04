<template>
	<div class="image-container" :style="`background-image: url('${file.url}')`">
		<div class="image-overlay">
			<img :src="file.url" alt="" class="img-fluid">
		</div>
	</div>

</template>

<script>
export default {
	props : ['file'],
	name  : "ImageFile"
};
</script>

<style scoped>
.image-container {
	background-size: cover;
	max-height: 700px;
	background-repeat: no-repeat;
	background-position: center center;
}

.image-container > .image-overlay {
	background-color: rgba(0, 0, 0, 0.5);
	display: flex;
	justify-content: center;
	align-items: center;
}

.image-container > .image-overlay > img {
	max-height: 700px;
}

</style>
