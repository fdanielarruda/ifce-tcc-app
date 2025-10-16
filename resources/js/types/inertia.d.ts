import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { Errors, Page, PageProps } from '@inertiajs/vue3';

interface FlashMessages {
    success?: string;
    error?: string;
    message?: string;
}

interface User {
    id: number;
    name: string;
    email: string;
}

interface CustomPageProps extends PageProps {
    auth: {
        user: User;
    };
    flash: FlashMessages;
}

declare module '@inertiajs/vue3' {
    export interface PageProps extends CustomPageProps { }
}

declare module '@inertiajs/core' {
    export interface PageProps extends CustomPageProps { }
}

declare global {
    var route: typeof ZiggyRoute;
}