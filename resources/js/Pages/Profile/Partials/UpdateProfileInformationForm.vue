<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, usePage, useForm ,router} from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    photo: user.photo,
})

const handlePhotoChange = (event) => {
    const file = event.target.files[0];
    const formData = new FormData();
    formData.append('image',file);
    router.post(route('image.upload'),formData,{
        preserveScroll:true,
        preserveState:true,
        forceFormData:true,
        onSuccess({props}){
            form.photo = props.inputs.url;
        }
    });
    
}

const submit = () => {
    form.patch(route('profile.update'),{
        preserveScroll:true,
        preserveState:true,
    });
} 


</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="submit" class="mt-6 space-y-6">
            <div class="flex">
                <div class="text-center">
                    <div class="w-36 h-36 mb-4 rounded-full block overflow-hidden border-2  border-slate-800">
                        <img :src="form.photo" class="w-full object-cover">
                    </div>
                    <label class="px-4 py-2 cursor-pointer text-white rounded-lg bg-slate-800" for="photo">Change
                        photo</label>
                    <input type="file" accept="image/*" @input='handlePhotoChange' id='photo' class="hidden">
                </div>
            </div>

            <div>
                <InputLabel for="name" value="Name" />

                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name"  autofocus
                    autocomplete="name" :error="form.errors.name ? true : false" />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" 
                    autocomplete="username" :error="form.errors.email ? true : false" />

                <InputError class="mt-2" :message=" form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <Link :href="route('verification.send')" method="post" as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Click here to re-send the verification email.
                    </Link>
                </p>

                <div v-show="status === 'verification-link-sent'" class="mt-2 font-medium text-sm text-green-600">
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
