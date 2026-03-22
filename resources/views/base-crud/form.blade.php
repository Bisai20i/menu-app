@extends('layouts.admin-layout')


@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>

                <nav aria-label="breadcrumb mb-2">
                    <ol class="breadcrumb small">
                        <li class="breadcrumb-item"><a href="{{ route($routePrefix . '.index') }}">List</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </nav>
                <h4 class="mb-0 fw-bold">{{ $title }}</h4>
            </div>
            <a href="{{ route($routePrefix . '.index') }}" class="btn btn-light border shadow-sm btn-sm">
                <i class="bx bx-arrow-back"></i> Back to List
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mb-2">
                <p class="fw-bold">Error while saving the record!</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">
                <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method($method)

                    <div class="row g-3 p-2">
                        @foreach ($fields as $name => $meta)
                            <div class="{{ $meta['column'] ?? 'col-md-6' }} mt-0 mb-3">

                                <!-- 1. Label Component -->
                                <x-form.label :for="$name" class="fw-bold mb-2" :required="$meta['required'] ?? false">
                                    {{ $meta['label'] }}
                                </x-form.label>

                                <!-- 2. Form Logic Based on Type -->
                                @if ($meta['type'] === 'select')
                                    <x-form.select :name="$name" :required="$meta['required'] ?? false" :options="$meta['options'] ?? ['' => 'No data found']" :selected="old($name, $item->$name ?? '')"
                                        class="form-select shadow-none" :help="$meta['help'] ?? ''" />
                                @elseif($meta['type'] === 'textarea')
                                    <x-form.text-area :name="$name" :required="$meta['required'] ?? false" :value="old($name, $item->$name ?? '')"
                                        :placeholder="$meta['placeholder'] ?? ''" :help="$meta['help'] ?? ''" />
                                @elseif($meta['type'] === 'editor')
                                    <x-form.text-editor :name="$name" :required="$meta['required'] ?? false" :value="old($name, $item->$name ?? '')"
                                        :help="$meta['help'] ?? ''" />
                                @elseif($meta['type'] === 'image')
                                    <div x-data="{
                                        imageUrl: @json($item->name ?? null),
                                        preview(event) {
                                            const file = event.target.files[0];
                                            if (file) {
                                                this.imageUrl = URL.createObjectURL(file);
                                            }
                                        }
                                    }">
                                        <input type="file" name="{{ $name }}"
                                            {{ isset($item) ? '' : $mets['required'] ?? 'required' }}
                                            class="form-control shadow-none @error($name) is-invalid @enderror"
                                            @change="preview" :value="imageUrl">

                                        <!-- Image Preview Area -->
                                        <div class="mt-3">
                                            <template x-if="imageUrl">
                                                <div class="position-relative d-inline-block">
                                                    <img :src="imageUrl" class="img-thumbnail shadow-sm"
                                                        style="max-height: 150px;">
                                                    <button type="button" @click="imageUrl = null"
                                                        class="btn btn-danger btn-sm position-absolute px-2 py-1 top-0 end-0 m-1 rounded-circle">
                                                        <i class="bx bx-x"></i>
                                                    </button>
                                                </div>
                                            </template>

                                            @if ($item && $item->$name && !old($name))
                                                <template x-if="!imageUrl">
                                                    <img src="{{ asset('storage/' . $item->$name) }}"
                                                        class="img-thumbnail shadow-sm" style="max-height: 150px;">
                                                </template>
                                            @endif
                                        </div>
                                    </div>
                                @elseif($meta['type'] === 'json')
                                    <x-form.json-field :name="$name" :required="$meta['required'] ?? false" :value="old($name, $item->$name ?? [])"
                                        :key-placeholder="$meta['key_placeholder'] ?? 'Key'" :value-placeholder="$meta['value_placeholder'] ?? 'Value'" :value-type="$meta['value_type'] ?? 'text'" :help="$meta['help'] ?? null" />
                                @elseif($meta['type'] === 'array')
                                    <x-form.array-field :name="$name" :required="$meta['required'] ?? false" :value="old($name, $item->$name ?? [])"
                                        :placeholder="$meta['placeholder'] ?? 'Item...'" :type="$meta['array_type'] ?? 'text'" :help="$meta['help'] ?? null" />
                                @else
                                    <!-- Default Input (text, email, number, password, etc.) -->
                                    <x-form.input :type="$meta['type']" :required="$meta['required'] ?? false" :name="$name"
                                        :help="$meta['help'] ?? ''" :value="$item->$name ?? ''" :placeholder="$meta['placeholder'] ?? ''"
                                        class="form-control shadow-none" />
                                @endif

                            </div>
                        @endforeach
                    </div>

                    <div class="mt-2 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route($routePrefix . '.index') }}" class="btn btn-light px-4 border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bx bx-save me-1"></i>
                            {{ $item->exists ? 'Update Changes' : 'Create Record' }}
                        </button>

                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
