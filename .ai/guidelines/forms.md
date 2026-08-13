# Forms

- Wrap every form field with `FormField` from `@/components/base/FormField.vue` so labels, hints, accessibility attributes, and validation errors remain consistent.
- Always prefer reusable components from `resources/js/components/base` for form controls and actions, including `Input`, `PasswordInput`, `Button`, `Dialog`, `Label`, and related primitives. Do not duplicate these components in page files.
- Use Inertia's `<Form>` component or its supported form API for submissions, and use Wayfinder controller actions or named route helpers instead of hardcoded backend URLs.
- Use Laravel Precognition for field-level validation. Validate fields on blur and revalidate on input only when the field is currently invalid, following the pattern in `resources/js/pages/Users.vue`.
- Pass field errors from the form slot to `FormField` and preserve the form's `processing`, `validating`, and `invalid` state for controls and submission feedback.
- Reuse existing base components and the established `FormField` pattern before creating new wrappers or page-local form primitives.
