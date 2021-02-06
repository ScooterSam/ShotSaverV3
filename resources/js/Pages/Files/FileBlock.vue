<template>
	<div class="block relative group flex flex-row bg-gray-900 hover:bg-gray-800 hover:no-underline group-hover:no-underline duration-150 ease-in-out transition-all border-gray-800 overflow-hidden shadow-md hover:shadow-2xl rounded-lg">

		<div class="absolute top-0 right-0 shadow-md  rounded-bl-lg flex flex-row overflow-hidden">
			<div class="flex flex-col  px-2 bg-blue-700">
				<p class=" text-xs mb-0 font-semibold text-gray-200 leading-loose tracking-wide uppercase">
					{{ upload.type }}
				</p>
			</div>
			<div class="flex flex-col  px-2 bg-gray-700">
				<p class=" text-xs mb-0 font-semibold text-gray-200 leading-loose tracking-wide uppercase">
					{{ upload.views }} View{{ upload.views === 1 ? '' : 's' }}</p>
			</div>
			<div class="flex flex-col px-2 bg-purple-600">
				<p class=" text-xs mb-0 font-semibold text-white leading-loose tracking-wide uppercase ">
					{{ upload.total_favourites }} Favourite{{ upload.total_favourites === 1 ? '' : 's' }}</p>
			</div>
		</div>
		<div class="absolute bottom-0 right-0 shadow-md rounded-tl-lg flex flex-row overflow-hidden">
			<a href="javascript:;"
			   v-if="upload.is_mine"
			   @click="remove(upload)"
			   :disabled="upload.deleting"
			   :class="{'opacity-50 cursor-not-allowed' : upload.deleting }"

			   class="px-3 hover:no-underline text-xs mb-0 font-semibold bg-red-500 hover:bg-red-600 text-red-100 hover:text-white leading-loose tracking-wide uppercase ">
				<template v-if="upload.deleting">
					<i class="fa fa-spinner mr-1 fa-spin"></i>
				</template>
				<template v-else>
					<i class="fa fa-trash mr-1"></i>
				</template>
				delete
			</a>
			<a href="javascript:;"
			   @click="favourite(upload)"
			   :disabled="upload.favouriting"
			   :class="{'opacity-50 cursor-not-allowed' : upload.favouriting, 'bg-purple-600 hover:bg-purple-600 text-purple-100 hover:text-white' : upload.favourited, 'bg-purple-400 hover:bg-purple-400 text-purple-700 hover:text-purple-800' : !upload.favourited }"
			   class=" px-3  hover:no-underline text-xs mb-0 font-semibold leading-loose tracking-wide uppercase ">
				<template v-if="upload.favouriting">
					<i class="fa fa-spinner mr-1 fa-spin"></i>
				</template>
				<template v-else>
					<i v-if="upload.favourited" class="fa text-red-200 fa-heart mr-1 animated heartBeat"></i>
					<i v-else class="fa fa-heart-o mr-1"></i>
				</template>
				Favourite
			</a>
		</div>

		<div class="bg-center bg-cover bg-no-repeat w-2/12 opacity-75 group-hover:opacity-100"
		     v-if="upload.thumb"
		     :style="{'background-image' : `url(${upload.thumb})`}">
		</div>

		<div class="h-full py-8 flex flex-row items-center justify-center text-gray-400 w-2/12 opacity-75 bg-smoke-200 group-hover:bg-smoke-100 group-hover:text-gray-300" v-if="!upload.thumb && upload.type === 'code'">
			<svg class="w-12 h-12"
			     fill="none"
			     stroke="currentColor"
			     viewBox="0 0 24 24"
			     xmlns="http://www.w3.org/2000/svg">
				<path stroke-linecap="round"
				      stroke-linejoin="round"
				      stroke-width="2"
				      d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
			</svg>
		</div>
		<div class="h-full py-8 flex flex-row items-center justify-center text-gray-400 w-2/12 opacity-75 bg-smoke-200 group-hover:bg-smoke-100 group-hover:text-gray-300" v-if="!upload.thumb && upload.type === 'text'">
			<svg class="w-12 h-12"
			     fill="none"
			     stroke="currentColor"
			     viewBox="0 0 24 24"
			     xmlns="http://www.w3.org/2000/svg">
				<path stroke-linecap="round"
				      stroke-linejoin="round"
				      stroke-width="2"
				      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
			</svg>
		</div>

		<div class="flex flex-row px-4 py-8 gap-6 align-items-center w-full">

			<div class="flex flex-col text-xs flex-1">
				<inertia-link :href="route('files.view', upload)"
				              class="font-semibold leading-loose text-gray-100 group-hover:text-gray-300 hover:no-underline">
					{{ upload.name }}
				</inertia-link>
				<p class="mb-0 font-light text-gray-400 leading-loose uppercase">
					{{ upload.created_at | from }}
				</p>
			</div>


			<div class="flex flex-col text-xs ">
				<p class="mb-0 font-semibold text-gray-200 leading-loose">{{ upload.size }}</p>
				<p class="mb-0 font-light text-gray-400 leading-loose uppercase">File Size</p>
			</div>
			<div class="flex flex-col text-xs flex-1">
				<p class="mb-0 font-semibold text-gray-200 leading-loose">
					{{ upload.private ? 'Private' : 'Public' }}
				</p>
				<p class="mb-0 font-light text-gray-400 leading-loose uppercase">Privacy</p>
			</div>

		</div>
	</div>
</template>

<script>
export default {
	name    : "FileBlock",
	props   : ['upload'],
	mounted()
	{
	},
	data()
	{
		return {};
	},
	methods : {
		favourite(upload)
		{
			this.$set(upload, 'favouriting', true);

			this.$inertia.post(
				route('files.favourite', upload),
				{},
				{
					preserveScroll : true,
					onFinish       : () => {
						this.$set(upload, 'favouriting', false);
					}
				}
			);
		},

		remove(upload)
		{
			if (!confirm('Are you sure you want to delete this file?')) return;

			this.$set(upload, 'deleting', true);

			this.$inertia.delete(
				route('files.delete', upload),
				{},
				{
					preserveScroll : true,
					onFinish       : () => {
						this.$set(upload, 'deleting', false);
					}
				}
			);
		}
	}
};
</script>

<style scoped lang="scss">

</style>
