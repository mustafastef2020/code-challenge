<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-light">

  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4 shadow-sm rounded" style="width: 100%; max-width: 400px;">
      <h3 class="text-center mb-4">Login</h3>
      <form x-data="createLoginForm()" @submit.prevent="submitForm">
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" :class="{ 'is-invalid': errors.email }" x-model="form.email" id="email" name="email" required autofocus>
          <div x-text="errors.email" class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" x-model="form.password" :class="{ 'is-invalid': errors.password }" required>
          <div x-text="errors.password" class="invalid-feedback"></div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-3" :disabled="submitting">Login</button>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function createLoginForm()
    {
        return {
            form: {
                email: '',
                password: '',
            },
            submitting: false,
            errors: {},
            async submitForm() {
                try {
                    this.submitting = true;
                    let response = await fetch('{{ route('login') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(this.form)
                    });

                    this.form.password = '';

                    if(! response.ok && response.status == 422) {
                        const errorData = await response.json();
                        const errorMessages = {};
                        for (const key in errorData.errors) {
                            if (errorData.errors.hasOwnProperty(key)) {
                                errorMessages[key] = errorData.errors[key][0];
                            }
                        }
                        this.errors = errorMessages;
                        this.submitting = false;
                    } else if (! response.ok && response.status == 401) {
                       const errorData = await response.json();
                      this.errors = {"email": errorData.message};
                      this.submitting = false;
                    } else {
                        const responseData = await response.json();
                        this.errors = {};
                        this.submitting = false;
                        localStorage.setItem("access_token", responseData.data.access_token);
                        localStorage.setItem("username", responseData.data.user.name);
                        window.location.href = "{{route('quotation.form')}}";
                    }
                } catch(error) {
                    alert('something went wrong ! please try again letter')
                }       
            }
        }
    }
</script>
</body>
</html>
