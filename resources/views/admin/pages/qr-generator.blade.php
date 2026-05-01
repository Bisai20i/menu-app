@extends('layouts.admin-layout')

@push('title', 'Menu - Table QR Generator')

@section('content')
    <livewire:table-qr-generator />

    {{-- QR Print Modal: needed for the Print Format buttons on each table card --}}
    <x-qr-print-modal />
@endsection
