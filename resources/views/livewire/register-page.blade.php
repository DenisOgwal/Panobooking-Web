

<section class="vh-100" style="background-color: #3352ff;">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
          <div class="card text-black" style="border-radius: 25px;">
            <div class="card-body p-md-3">
              <div class="row justify-content-center">
                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
  
                  <p class="text-center h2 fw-bold mb-3 mx-1 mx-md-4 mt-4">
                    <a href="/">Home</a>&nbsp;
                    <a href="/Login">Login</a>&nbsp;
                    Sign up</p>
                
                  <form class="mx-1 mx-md-4" wire:submit.prevent="submit" >
                     @csrf
                    <div class="d-flex flex-row align-items-center mb-2">
                      <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        <input type="text" class="form-control" name="name" wire:model="name"/>
                       
                        <label class="form-label" for="form3Example1c">Your Name</label>
                      </div>
                    </div>
  
                    <div class="d-flex flex-row align-items-center mb-2">
                      <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        <input type="email"  class="form-control" name="email" wire:model="email"/>
                        <label class="form-label" for="form3Example3c">Your Email</label>
                      </div>
                    </div>

                    <div class="d-flex flex-row align-items-center mb-2">
                        <i class="fas fa-phone fa-lg me-3 fa-fw"></i>
                          <div class="form-outline align-items-center col-md-3 mb-2">
                            @error('code') <span class="text-danger">{{ $message }}</span> @enderror
                            <input type="text" class="form-control form-control-lg" name="code" wire:model="code" />
                            <label class="form-label" for="form3Examplea4">Code +</label>
                        </div>
                      
                          <div class="form-outline align-items-center col-md-8 mb-2">
                            @error('phonenum') <span class="text-danger">{{ $message }}</span> @enderror
                            <input type="number" class="form-control form-control-lg" name="phonenum" wire:model="phonenum" />
                            <label class="form-label" for="form3Examplea8">Phone Number</label>
                        </div>
                      </div>
    
  
                    <div class="d-flex flex-row align-items-center mb-2">
                      <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        <input type="password" class="form-control" name="password"  wire:model="password"/>
                        <label class="form-label" for="form3Example4c">Password</label>
                      </div>
                    </div>
  
                    <div class="d-flex flex-row align-items-center mb-2">
                      <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        @error('confirmpassword') <span class="text-danger">{{ $message }}</span> @enderror
                        <input type="password"  class="form-control" name="confirmpassword" wire:model="confirmpassword"/>
                        <label class="form-label" for="form3Example4cd">Repeat your password</label>
                      </div>
                    </div>
  
                    <div class="form-check d-flex justify-content-center mb-3">
                      <input
                        class="form-check-input me-2"
                        type="checkbox"
                        value=""
                        id="form2Example3c"
                      />
                      <label class="form-check-label" for="form2Example3">
                        I agree all statements in <a href="TermsAndConditions">Terms of service</a>
                      </label>
                    </div>
  
                    <div class="d-flex justify-content-center mx-4 mb-1 mb-lg-1">
                      <button type="submit" class="btn btn-primary btn-lg">Register</button>
                    </div>
                  </form>
  
                </div>
                <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
  
                    <img src="{{asset('images/Register Image 2.jpg')}}" class="img-fluid" alt="Sample image">
  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  