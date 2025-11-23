import { toTypedSchema } from '@vee-validate/zod';
import * as z from 'zod';

export const userSchema = z.object({
    name: z.string().min(1, 'Name is required').max(255, 'Name must not exceed 255 characters'),
    email: z.email('Invalid email address').min(1, 'Email is required'),
    password: z.string().min(8, 'Password must be at least 8 characters').optional().or(z.literal('')),
    password_confirmation: z.string().optional(),
    role: z.enum(['admin', 'member'], { message: 'Role is required' }),
}).refine((data) => {
    if (data.password && data.password.length > 0) {
        return data.password === data.password_confirmation;
    }
    return true;
}, {
    message: "Passwords don't match",
    path: ["password_confirmation"],
});

export const userCreateSchema = z.object({
    name: z.string().min(1, 'Name is required').max(255, 'Name must not exceed 255 characters'),
    email: z.email('Invalid email address').min(1, 'Email is required'),
    password: z.string().min(8, 'Password must be at least 8 characters'),
    password_confirmation: z.string().min(1, 'Password confirmation is required'),
    role: z.enum(['admin', 'member'], { message: 'Role is required' }),
}).refine((data) => data.password === data.password_confirmation, {
    message: "Passwords don't match",
    path: ["password_confirmation"],
});

export const typedUserSchema = toTypedSchema(userSchema);
export const typedUserCreateSchema = toTypedSchema(userCreateSchema);

// Login schema
export const loginSchema = z.object({
    email: z.email('Invalid email address').min(1, 'Email is required'),
    password: z.string().min(1, 'Password is required'),
    remember: z.boolean().optional(),
});

export const typedLoginSchema = toTypedSchema(loginSchema);

// Register schema
export const registerSchema = z.object({
    name: z.string().min(1, 'Name is required').max(255),
    email: z.email('Invalid email address').min(1, 'Email is required'),
    password: z.string().min(8, 'Password must be at least 8 characters'),
    password_confirmation: z.string().min(1, 'Password confirmation is required'),
}).refine((data) => data.password === data.password_confirmation, {
    message: "Passwords don't match",
    path: ["password_confirmation"],
});

export const typedRegisterSchema = toTypedSchema(registerSchema);

// Forgot password schema
export const forgotPasswordSchema = z.object({
    email: z.email('Invalid email address').min(1, 'Email is required'),
});

export const typedForgotPasswordSchema = toTypedSchema(forgotPasswordSchema);

// Reset password schema
export const resetPasswordSchema = z.object({
    token: z.string(),
    email: z.string().min(1, 'Email is required').email('Invalid email address'),
    password: z.string().min(8, 'Password must be at least 8 characters'),
    password_confirmation: z.string().min(1, 'Password confirmation is required'),
}).refine((data) => data.password === data.password_confirmation, {
    message: "Passwords don't match",
    path: ["password_confirmation"],
});

export const typedResetPasswordSchema = toTypedSchema(resetPasswordSchema);

// Confirm password schema
export const confirmPasswordSchema = z.object({
    password: z.string().min(1, 'Password is required'),
});

export const typedConfirmPasswordSchema = toTypedSchema(confirmPasswordSchema);

// Update profile schema
export const updateProfileSchema = z.object({
    name: z.string().min(1, 'Name is required').max(255),
    email: z.email('Invalid email address').min(1, 'Email is required'),
});

export const typedUpdateProfileSchema = toTypedSchema(updateProfileSchema);

// Update password schema
export const updatePasswordSchema = z.object({
    current_password: z.string().min(1, 'Current password is required'),
    password: z.string().min(8, 'Password must be at least 8 characters'),
    password_confirmation: z.string().min(1, 'Password confirmation is required'),
}).refine((data) => data.password === data.password_confirmation, {
    message: "Passwords don't match",
    path: ["password_confirmation"],
});

export const typedUpdatePasswordSchema = toTypedSchema(updatePasswordSchema);

// Delete user schema
export const deleteUserSchema = z.object({
    password: z.string().min(1, 'Password is required'),
});

export const typedDeleteUserSchema = toTypedSchema(deleteUserSchema);
