<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .result-card {
            display: none;
            margin-top: 2rem;
        }
        .age-help {
            font-size: 0.875rem;
            color: #6c757d;
        }

    </style>
     <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>

    <div class="container" x-data="createQuotationForm()">
        <!-- Quotation Form -->
        <div class="form-container">
            <h3 class="text-center mb-4">
                <i class="fas fa-file-invoice-dollar text-primary me-2"></i>
                Quotation Calculator
            </h3>
            
            <form id="quotationForm" @submit.prevent="submitForm">
                <div class="mb-3">
                    <label for="age" class="form-label">
                        <i class="fas fa-users me-2"></i>Ages of Travelers
                    </label>
                    <input type="text" class="form-control" id="age" name="age" :class="{ 'is-invalid': errors.age }" x-model="form.age"
                           placeholder="e.g., 28,35,42" required>
                    <div class="age-help mt-1">
                        <i class="fas fa-info-circle me-1"></i>
                        Enter ages separated by commas. Ages must be between 18-70.
                    </div>
                    <div x-text="errors.age" class="invalid-feedback"></div>
                </div>

                <div class="mb-3">
                    <label for="currency_id" class="form-label">
                        <i class="fas fa-money-bill-wave me-2"></i>Currency
                    </label>
                    <select class="form-select" id="currency_id" name="currency_id" :class="{ 'is-invalid': errors.currency_id }" x-model="form.currency_id" required>
                        <option value="">Select Currency</option>
                        @foreach($currencies as $currency)
                        <option value="{{$currency->value}}">{{ $currency->label() }}</option>
                        @endforeach
                    </select>
                    <div x-text="errors.currency_id" class="invalid-feedback"></div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">
                                <i class="fas fa-calendar-alt me-2"></i>Start Date
                            </label>
                            <input type="date" class="form-control" id="start_date" name="start_date" :class="{ 'is-invalid': errors.start_date }" x-model="form.start_date" required>
                            <div x-text="errors.start_date" class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="end_date" class="form-label">
                                <i class="fas fa-calendar-check me-2"></i>End Date
                            </label>
                            <input type="date" class="form-control" id="end_date" name="end_date" :class="{ 'is-invalid': errors.end_date }" x-model="form.end_date" required>
                            <div x-text="errors.end_date" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <span class="me-2" role="status"></span>
                        <i class="fas fa-calculator me-2"></i>
                        Calculate Quote
                    </button>
                </div>
            </form>

            <!-- Result Card -->
            <div class="result-card">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            Your Quote is Ready!
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Total:</strong></p>
                                <h3 class="text-success mb-0" id="totalAmount"></h3>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Quote ID:</strong></p>
                                <h4 class="text-muted mb-0" id="quoteId"></h4>
                            </div>
                        </div>
                        <hr>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-outline-primary" x-on:click="resetForm">
                                <i class="fas fa-redo me-2"></i>Calculate Another
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   
    <script>
    function createQuotationForm()
    {
        return {
            form: {
                age: '',
                currency_id: '',
                start_date: '',
                end_date: '',
            },
            submitting: false,
            errors: {},
            async submitForm() {
                try {
                    this.submitting = true;
                    let response = await fetch('{{ route('quotation.store') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                        },
                        body: JSON.stringify(this.form)
                    });

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
                      this.errors = {};
                      this.submitting = false;
                      alert(errorData.message);
                    } else {
                        const responseData = await response.json();
                        this.errors = {};
                        this.submitting = false;
                        this.displayResult(responseData.data);
                    }
                } catch(error) {
                    alert('something went wrong ! please try again letter')
                }       
            },
            displayResult(response) {
            const totalAmount = document.getElementById('totalAmount');
            const quoteId = document.getElementById('quoteId');
            const resultCard = document.querySelector('.result-card');
            
            totalAmount.textContent = `${response.total} ${response.currency_id}`;
            quoteId.textContent = `#${response.quotation_id}`;
            
            resultCard.style.display = 'block';
            resultCard.scrollIntoView({ behavior: 'smooth' });
        },
        resetForm() {
            this.form = {};
            document.querySelector('.result-card').style.display = 'none';
        }
        }
    }
</script>
</body>
</html>