export function register(): string {
    return '/register';
}

export function forgotPassword(): string {
    return '/forgot-password';
}

export function resetPassword(token: string): string {
    return `/reset-password/${token}`;
}
