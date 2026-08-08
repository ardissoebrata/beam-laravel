---
paths:
  - 'app/Http/Middleware/HandleInertiaRequests.php|resources/js/pages/auth/**|resources/js/pages/Welcome.vue'
---

# Pages

## Gate auth UI via Fortify feature flags
For guest auth UI, use shared Inertia props under auth.features (registration, resetPasswords) sourced from Features::enabled(...). Hide Register/Forgot Password links when the corresponding Fortify feature is disabled, so frontend matches backend route availability.
