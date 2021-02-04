<template>

	<app-layout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-200 leading-tight">
				Your Files
			</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div>
					<div class="flex flex-row items-center justify-between py-4" v-if="files">
						<div class="">

							<div class="relative z-0 inline-flex shadow-sm rounded-md">
								<button type="button"
								        :disabled="paginating || (files.meta.current_page <= 1)"
								        @click="getUploads('back')"
								        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-800 bg-gray-900 text-sm font-medium text-gray-400 hover:bg-gray-700 focus:z-10 transition focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
									<span class="sr-only">Previous</span>
									<!-- Heroicon name: solid/chevron-left -->
									<svg class="h-5 w-5"
									     xmlns="http://www.w3.org/2000/svg"
									     viewBox="0 0 20 20"
									     fill="currentColor"
									     aria-hidden="true">
										<path fill-rule="evenodd"
										      d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
										      clip-rule="evenodd" />
									</svg>
								</button>
								<button type="button"
								        :disabled="paginating || (files.meta.current_page >= files.meta.last_page)"
								        @click="getUploads('forward')"
								        class="-ml-px relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-800 bg-gray-900 text-sm font-medium text-gray-400 hover:bg-gray-700 transition focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
									<span class="sr-only">Next</span>
									<!-- Heroicon name: solid/chevron-right -->
									<svg class="h-5 w-5"
									     xmlns="http://www.w3.org/2000/svg"
									     viewBox="0 0 20 20"
									     fill="currentColor"
									     aria-hidden="true">
										<path fill-rule="evenodd"
										      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
										      clip-rule="evenodd" />
									</svg>
								</button>
							</div>

						</div>
						<div class="text-gray-500">
							Page <strong class="text-gray-300">{{ files.meta.current_page }}</strong> of
							<strong class="text-gray-300">{{ files.meta.last_page }}</strong>
							|
							<strong class="text-gray-300">{{ files.meta.total }}</strong>
							results
						</div>
						<div class="flex flex-row items-center space-x-2">

							<dropdown-menu v-model="showFilterDropdown" class="relative">
								<button class="bg-blue-500 px-2 py-1 rounded shadow text-white tracking-wide">
									Filter: <strong>{{ this.filterText() }}</strong> <span class="caret"></span>
								</button>
								<div slot="dropdown" class="absolute z-10 bg-gray-900 rounded-b shadow-lg w-full ">

									<a class="dropdown-item transition hover:bg-gray-800 px-2 py-2 block hover:no-underline hover:text-gray-300 text-white"
									   href="javascript:;"
									   @click="changeFilter('created')">
										Created
									</a>
									<a class="dropdown-item transition hover:bg-gray-800 px-2 py-2 block hover:no-underline hover:text-gray-300 text-white"
									   href="javascript:;"
									   @click="changeFilter('size')">
										Size
									</a>
									<a class="dropdown-item transition hover:bg-gray-800 px-2 py-2 block hover:no-underline hover:text-gray-300 text-white"
									   href="javascript:;"
									   @click="changeFilter('type')">
										Type
									</a>
									<a class="dropdown-item transition hover:bg-gray-800 px-2 py-2 block hover:no-underline hover:text-gray-300 text-white"
									   href="javascript:;"
									   @click="changeFilter('views')">
										Views
									</a>
									<a class="dropdown-item transition hover:bg-gray-800 px-2 py-2 block hover:no-underline hover:text-gray-300 text-white"
									   href="javascript:;"
									   @click="changeFilter('favourites')">
										Favourites
									</a>
								</div>
							</dropdown-menu>

							<dropdown-menu v-model="showDirectionDropdown" class="relative">
								<button class="bg-blue-500 px-2 py-1 rounded shadow text-white tracking-wide">
									Order: <strong>{{
										filters.order === 'desc' ? 'Descending' : 'Ascending'
									               }}</strong>
								</button>
								<div slot="dropdown" class="absolute z-10 bg-gray-900 rounded-b shadow-lg w-full ">
									<a class="dropdown-item transition hover:bg-gray-800 px-2 py-2 block hover:no-underline hover:text-gray-300 text-white"
									   href="javascript:;"
									   @click="changeOrder('desc')">Descending</a>
									<a class="dropdown-item transition hover:bg-gray-800 px-2 py-2 block hover:no-underline hover:text-gray-300 text-white"
									   href="javascript:;"
									   @click="changeOrder('asc')">Ascending</a>
								</div>
							</dropdown-menu>

						</div>
					</div>

					<div v-if="paginating || loading" class="text-center py-5">
						<i class="fa fa-spinner-third fa-spin fa-2x leading-loose"></i>
						<h3 class="leading-loose">Loading...</h3>
					</div>
					<div v-else>
						<div class="flex flex-col gap-6" v-if="files && files.data.length">

							<file-block :key="`upload-${upload.id}`"
							            :upload="upload"
							            v-for="upload in files.data"></file-block>

						</div>

						<div v-else class="text-center py-5">
							<h4>No Uploads</h4>
							<p>
								You have not uploaded any files yet.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</app-layout>

</template>

<script>
import AppLayout    from '@/Layouts/AppLayout';
import FileBlock    from '@/Pages/Files/FileBlock';
import DropdownMenu from '@innologica/vue-dropdown-menu';


export default {
	props      : ['files'],
	name       : "List",
	components : {
		FileBlock,
		AppLayout,
		DropdownMenu
	},

	data()
	{
		return {

			paginating : false,
			loading    : false,
			error      : null,
			page       : 1,

			showFilterDropdown : false,
			showDirectionDropdown : false,

			uploadToPreview : null,

			filters : {
				filter : 'created',
				order  : 'desc',
			}
		};
	},

	methods : {

		changeFilter(filter)
		{
			this.filters.filter = filter;
			this.page           = 1;
			this.getUploads();
		},

		changeOrder(filter)
		{
			this.filters.order = filter;
			this.page          = 1;
			this.getUploads();
		},

		/**
		 * Get a paginated list of the users uploads
		 */
		getUploads(type = null)
		{
			let page = this.page;

			if (type === 'forward') {
				if (this.files && this.files.meta.last_page === page) return;
				page++;
				this.paginating = true;

			} else if (type === 'back') {
				if (page === 1) return;

				page--;
				this.paginating = true;
			}

			this.loading = true;

			this.$inertia.get(route('files.list'), {
				page     : page,
				order_by : this.filters.filter,
				order    : this.filters.order
			}, {
				replace  : true,
				onFinish : () => {
					this.loading = false;
				}
			});
		},

		filterText()
		{
			switch (this.filters.filter) {
				case "created":
					return "Created";
				case "size":
					return "Size";
				case "type":
					return "Type";
				case "views":
					return "Views";
				case "favourites":
					return "Favourites";
			}
		}

	}
};
</script>

<style scoped>

</style>
