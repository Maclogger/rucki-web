import {defineStore} from 'pinia';


export enum ToastSeverity {
    INFO = "info",
    WARNING = "warning",
    ERROR = "error",
    SUCCESS = "success",
}

export interface ToastConfig {
    alertClass: string;
    icon: [string, string];
    textColor: string;
}


export interface ToastProps {
    message: string;
    severity?: ToastSeverity;
    id?: number;
}

interface ToastsStoreState {
    toasts: Map<number, ToastProps>,
    currentIndex: number,
}

const toastConfigMap: Record<ToastSeverity, ToastConfig> = {
    [ToastSeverity.INFO]: {
        alertClass: "alert-info",
        icon: ["fas", "info-circle"],
        textColor: "text-my-white",
    },
    [ToastSeverity.WARNING]: {
        alertClass: "alert-warning",
        icon: ["fas", "exclamation-triangle"],
        textColor: "text-my-black",
    },
    [ToastSeverity.ERROR]: {
        alertClass: "alert-error",
        icon: ['fad', 'circle-xmark'],
        textColor: "text-my-white",
    },
    [ToastSeverity.SUCCESS]: {
        alertClass: "alert-success",
        icon: ["fas", "check-circle"],
        textColor: "text-my-white",
    },
};


export const useToastsStore = defineStore("toastsStore", {
    state(): ToastsStoreState {
        return {
            toasts: new Map<number, ToastProps>(),
            currentIndex: 0,
        };
    },

    actions: {
        displayToast(toast: ToastProps) {
            toast.id = this.currentIndex;
            this.toasts.set(toast.id, toast);
            this.currentIndex++;
            setTimeout(() => {
                this.closeToast((toast.id!));
            }, 5000);
        },

        clearToasts() {
            this.toasts.clear();
            this.currentIndex = 0;
        },

        closeToast(toastId: number) {
            this.toasts.delete(toastId);
            if (this.toasts.size === 0) {
                this.currentIndex = 0;
            }
        },

        getToastConfig(toastId: number) {
            return toastConfigMap[this.toasts.get(toastId)?.severity || ToastSeverity.INFO];
        },

        getToastInfo(toastId: number) {
            const severity = this.toasts.get(toastId)?.severity || ToastSeverity.INFO;
            const message = this.toasts.get(toastId)?.message ?? "Niečo je zle.";
            return {
                ...this.getToastConfig(toastId),
                severity,
                message
            };
        }
    },

});


