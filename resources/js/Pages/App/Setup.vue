<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                Configure ShotSaver with <a class="text-blue-300 font-semibold hover:text-blue-400" href="https://getsharex.com/" target="_blank">ShareX</a>
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class=" space-y-8">
                    <div class="overflow-hidden shadow-xl sm:rounded-lg p-6 bg-gray-700">
                        <div>
                            <jet-application-logo class="block h-12 w-auto" />
                        </div>

                        <div class="mt-8 text-2xl text-white">
                            First of all, welcome!
                        </div>

                        <div class="mt-6 text-gray-300">
                            ShotSaver provides a simple way to upload your screenshots, videos and text/code files and share them with the world(or keep them private).
                        </div>
                        <div class="mt-3 text-gray-300">
                            ShareX provides a simple way to add a "custom uploader". You can download the custom uploader files below, they can be opened with ShareX and you'll be good to go!
                        </div>
                    </div>

                    <div class="overflow-hidden shadow-xl sm:rounded-lg p-6 bg-gray-700 flex flex-row items-center space-x-8">

                        <div class="flex-1 ">
                            <div class="text-2xl text-white">
                                Private Uploads
                            </div>
                            <div class="text-sm text-gray-300 mt-1.5">
                                This will set all files you upload to private. If you want to share them publicly, you will have to change the file privacy by going to the file url and clicking edit.
                            </div>
                        </div>

                        <SecondaryButton :href="route('setup.download', 'private')" as="a" class="mt-4">Download</SecondaryButton>

                    </div>

                    <div class="overflow-hidden shadow-xl sm:rounded-lg p-6 bg-gray-700 flex flex-row items-center space-x-8">

                        <div class="flex-1 ">
                            <div class="text-2xl text-white">
                                Public Uploads
                            </div>
                            <div class="text-sm text-gray-300 mt-1.5">
                                This will set all files you upload to public. If you want to keep it private, you will have to change the file privacy by going to the file url and clicking edit.
                            </div>
                        </div>

                        <SecondaryButton :href="route('setup.download', 'public')" as="a" class="mt-4">Download</SecondaryButton>

                    </div>

                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import JetApplicationLogo from '@/Jetstream/ApplicationLogo';
import AppLayout          from '@/Layouts/AppLayout';
import Welcome            from '@/Jetstream/Welcome';
import SecondaryButton    from '@/Jetstream/SecondaryButton';


export default {
    components : {
        AppLayout,
        Welcome,
        JetApplicationLogo,
        SecondaryButton
    },
};
</script>
