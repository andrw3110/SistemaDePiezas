<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
      <Head title="Log in" />
  
      <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
      </div>
  
      <form @submit.prevent="submit" class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto mt-10">
        <div>
          <InputLabel for="email" value="Email" class="font-semibold text-gray-700" />
  
          <TextInput
            id="email"
            type="email"
            :class="[
              'mt-1 block w-full border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500',
              form.errors.email ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300'
            ]"
            v-model="form.email"
            required
            autofocus
            autocomplete="username"
          />
  
          <InputError class="mt-2 text-red-600 text-sm" :message="form.errors.email" />
        </div>
  
        <div class="mt-4">
          <InputLabel for="password" value="Password" class="font-semibold text-gray-700" />
  
          <TextInput
            id="password"
            type="password"
            :class="[
              'mt-1 block w-full border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500',
              form.errors.password ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300'
            ]"
            v-model="form.password"
            required
            autocomplete="current-password"
          />
  
          <InputError class="mt-2 text-red-600 text-sm" :message="form.errors.password" />
        </div>
  
        <div class="block mt-4">
          <label class="flex items-center cursor-pointer">
            <Checkbox name="remember" v-model:checked="form.remember" />
            <span class="ms-2 text-sm text-gray-600 select-none">Remember me</span>
          </label>
        </div>
  
        <div class="flex items-center justify-end mt-6">
          <Link
            v-if="canResetPassword"
            :href="route('password.request')"
            class="underline text-sm text-indigo-600 hover:text-indigo-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Forgot your password?
          </Link>
  
          <PrimaryButton
            class="ms-4 px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md transition-opacity duration-300"
            :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
            :disabled="form.processing"
          >
            Log in
          </PrimaryButton>
        </div>
      </form>
    </GuestLayout>
</template>
