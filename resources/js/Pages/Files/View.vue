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
							<CodeFile :file="file" :contents="contents" v-if="['code', 'text'].includes(file.type)"></CodeFile>
						</div>

						<div v-if="$page.props.user" class="p-4 flex flex-row items-center space-x-4">

							<button @click="editFile"
							        class="text-green-500 rounded focus:outline-none px-2 py-1 border border-green-500 hover:border-green-400 hover:text-green-400 transition ">
								Edit
							</button>
							<button @click="toggleFavourited"
							        class="flex flex-row items-center text-indigo-500 rounded focus:outline-none px-2 py-1 border border-indigo-500 hover:border-indigo-400 hover:text-indigo-400 transition ">
								<Spinner v-if="file.favouriting" />

								<svg class="w-6 h-6 mr-1"
								     v-if="file.favourited"
								     fill="currentColor"
								     viewBox="0 0 20 20"
								     xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd"
									      d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
									      clip-rule="evenodd"></path>
								</svg>
								<svg class="w-6 h-6 mr-1"
								     v-else
								     fill="none"
								     stroke="currentColor"
								     viewBox="0 0 24 24"
								     xmlns="http://www.w3.org/2000/svg">
									<path stroke-linecap="round"
									      stroke-linejoin="round"
									      stroke-width="2"
									      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
								</svg>
								Favourite
							</button>
							<a :href="file.url"
							   download
							   class="text-white rounded focus:outline-none px-2 py-1 bg-gray-500 hover:bg-gray-600 hover:text-gray-100 transition ">
								Download
							</a>

						</div>

					</div>


					<DialogModal :show="editing" @close="editing = false">
						<template #title>
							Edit file information
						</template>

						<template #content>
							<div class="col-span-6 sm:col-span-4">
								<Label for="description" value="Description" />
								<TextArea id="description"
								          type="text"
								          class="mt-1 block w-full"
								          v-model="updateFileForm.description"
								          autofocus />
								<InputError :message="updateFileForm.errors.description" class="mt-2" />
							</div>
							<div class="col-span-6 sm:col-span-4 mt-4">

								<p class="text-gray-300 text-lg">
									Should this file be accessible by anybody else?
								</p>

								<label class="flex items-center mt-2">
									<Checkbox :value="updateFileForm.private" v-model="updateFileForm.private" />
									<span class="ml-2 text-sm text-gray-400">{{
											updateFileForm.private ? 'File is private' : 'File is public'
									                                         }}</span>
								</label>

								<p class="text-gray-300 mt-3">
									When changing file privacy, it can take a little while for these changes to show due
									to caching
								</p>
							</div>
						</template>

						<template #footer>
							<SecondaryButton @click.native="editing = false">
								Nevermind
							</SecondaryButton>

							<Button class="ml-2"
							        @click.native="save"
							        :class="{ 'opacity-25': updateFileForm.processing }"
							        :disabled="updateFileForm.processing">
								Save
							</Button>
						</template>
					</DialogModal>

				</div>
			</div>
		</div>
	</app-layout>
</template>

<script>
import Button          from '@/Jetstream/Button';
import Checkbox        from '@/Jetstream/Checkbox';
import DialogModal     from '@/Jetstream/DialogModal';
import InputError      from '@/Jetstream/InputError';
import Label           from '@/Jetstream/Label';
import SecondaryButton from '@/Jetstream/SecondaryButton';
import TextArea        from '@/Jetstream/TextArea';
import AppLayout       from '@/Layouts/AppLayout';
import CodeFile        from '@/Pages/Files/FileTypes/CodeFile';
import ImageFile       from '@/Pages/Files/FileTypes/ImageFile';
import VideoFile       from '@/Pages/Files/FileTypes/VideoFile';
import Spinner         from '@/Pages/Files/Spinner';


export default {
	props      : ['file', 'contents'],
	components : {
		Spinner,
		CodeFile,
		Checkbox,
		TextArea,
		InputError,
		Label,
		Button,
		SecondaryButton,
		ImageFile,
		VideoFile,
		AppLayout,
		DialogModal
	},

	created()
	{
		if (this.shouldRequestContents && !this.contents)
			this.$inertia.visit(route('files.view', this.file), {
				only : ['contents'],
			});
	},

	data()
	{
		return {
			editing : false,

			updating : false,

			updateFileForm : this.$inertia.form({
				description : "",
				private     : false,
			}),
		};
	},
	methods : {
		editFile()
		{
			this.updateFileForm.description = this.file.description;
			this.updateFileForm.private     = this.file.private;

			this.editing = true;
		},
		save()
		{
			this.updateFileForm.put(route('files.update', this.file), {
				only : this.shouldRequestContents ? ['file' , 'contents'] : ['file'],
				preserveScroll : true,
				preserveState  : true,
				onSuccess      : () => (this.editing = false),
			});
		},
		toggleFavourited()
		{
			this.$set(this.file, 'favouriting', true);

			this.$inertia.post(
				route('files.favourite', this.file),
				{},
				{
					only : this.shouldRequestContents ? ['file' , 'contents'] : ['file'],
					preserveScroll : true,
					preserveState : true,
					onFinish       : () => {
						this.$set(this.file, 'favouriting', false);
					}
				}
			);
		}
	},
	computed : {
		shouldRequestContents(){
			return ['text', 'code'].includes(this.file.type);

		}
	}
};
</script>
