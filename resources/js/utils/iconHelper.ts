import {
    ShoppingCartIcon,
    BanknotesIcon,
    BriefcaseIcon,
    ChartBarIcon,
    HomeIcon,
    TruckIcon,
    FaceSmileIcon,
    HeartIcon,
    BookOpenIcon,
    QuestionMarkCircleIcon,
} from "@heroicons/vue/24/outline";

export const getIconComponent = (iconType?: string) => {
    const icons: Record<string, any> = {
        "shopping-cart": ShoppingCartIcon,
        banknotes: BanknotesIcon,
        briefcase: BriefcaseIcon,
        "chart-bar": ChartBarIcon,
        home: HomeIcon,
        car: TruckIcon,
        "face-smile": FaceSmileIcon,
        heart: HeartIcon,
        "book-open": BookOpenIcon,
        "question-mark-circle": QuestionMarkCircleIcon,
    };

    return icons[iconType || "question-mark-circle"] || QuestionMarkCircleIcon;
};

export const getAvailableIcons = () => {
    return [
        { value: "shopping-cart", label: "Compras" },
        { value: "banknotes", label: "Dinheiro" },
        { value: "briefcase", label: "Trabalho" },
        { value: "chart-bar", label: "Investimentos" },
        { value: "home", label: "Casa" },
        { value: "car", label: "Transporte" },
        { value: "face-smile", label: "Lazer" },
        { value: "heart", label: "Saúde" },
        { value: "book-open", label: "Educação" },
        { value: "question-mark-circle", label: "Outros" },
    ];
};