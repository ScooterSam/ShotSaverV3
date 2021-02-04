<template>
	<app-layout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-200 leading-tight">
				{{ file.name }}
			</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg">

					<div class="flex flex-col w-full shadow-xl">
						<div class="w-full p-4">
							<div class="flex flex-row items-center space-x-2">
								<div class="flex flex-row items-center space-x-2">
									<span class="text-sm tracking-wide text-white">File Type:</span>
									<div class="bg-blue-500 rounded px-1 py-0.5 text-white uppercase tracking-wide">
										{{ file.type }}
									</div>
								</div>
								<div class="flex flex-row items-center space-x-2">
									<span class="text-sm tracking-wide text-white">File Size:</span>
									<div class="bg-blue-500 rounded px-1 py-0.5 text-white uppercase tracking-wide">
										{{ file.size }}
									</div>
								</div>
								<div class="flex flex-row items-center space-x-2">
									<span class="text-sm tracking-wide text-white">Privacy:</span>
									<div class="bg-blue-500 rounded px-1 py-0.5 text-white uppercase tracking-wide">
										{{ file.private ? 'Private' : 'Public' }}
									</div>
								</div>
							</div>
						</div>

						<div>
							<VideoFile :file="file" v-if="file.type === 'video'" />
							<ImageFile :file="file" v-if="file.type === 'image'" />
						</div>

					</div>


				</div>
			</div>
		</div>
	</app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';
import ImageFile from '@/Pages/Files/FileTypes/ImageFile';
import VideoFile from '@/Pages/Files/FileTypes/VideoFile';


export default {
	props      : ['file'],
	components : {
		ImageFile,
		VideoFile,
		AppLayout,
	},
};
</script>
