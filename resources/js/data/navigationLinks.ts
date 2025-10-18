import {
    UserIcon,
    HomeIcon,
    ArrowLeftEndOnRectangleIcon,
    PlusCircleIcon,
    ArrowsRightLeftIcon
} from '@heroicons/vue/24/solid';

interface NavigationLink {
    label?: string;
    icon?: any;
    route?: string;
    condition?: boolean | ((isRoot: boolean) => boolean);
    method?: string;
    as?: string;
    textClass?: string;
    iconClass?: string;
    type?: 'link' | 'separator';
    children?: NavigationLink[];
}

export const staticNavigationLinks: NavigationLink[] = [
    {
        label: 'Dashboard',
        icon: HomeIcon,
        route: 'dashboard',
        type: 'link',
    },
    {
        label: 'Transações',
        icon: ArrowsRightLeftIcon,
        route: 'transactions.index',
        type: 'link',
    },
    {
        label: 'Nova Transação',
        icon: PlusCircleIcon,
        route: 'transactions.create',
        type: 'link',
    },

    { type: 'separator' },
    {
        label: 'Perfil',
        icon: UserIcon,
        route: 'profile.edit',
        type: 'link',
    },
    {
        label: 'Sair',
        icon: ArrowLeftEndOnRectangleIcon,
        route: 'logout',
        method: 'post',
        as: 'button',
        textClass: 'text-red-500',
        iconClass: 'text-red-500',
        type: 'link',
    },
];