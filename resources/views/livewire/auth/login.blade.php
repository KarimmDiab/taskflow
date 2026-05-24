<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RYO — Sign In / Join</title>
  <meta name="description" content="Sign in to RYO. Early access, order tracking, and a refined member experience.">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=DM+Sans:wght@300;400;500&family=Space+Grotesk:wght@400;500&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'ryo-black': '#0A0A0A',
            'ryo-white': '#F8F6F2',
            'ryo-gray-100': '#EDEDEB',
            'ryo-gray-200': '#D5D3CF',
            'ryo-gray-400': '#9C9A96',
            'ryo-gray-700': '#3D3D3A',
            'ryo-cream': '#F2EEE8',
          },
          fontFamily: {
            display: ['Cormorant Garamond', 'serif'],
            body: ['DM Sans', 'sans-serif'],
            label: ['Space Grotesk', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'DM Sans', sans-serif;
      background: #F8F6F2;
      color: #0A0A0A;
      min-height: 100vh;
    }
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: #F8F6F2; }
    ::-webkit-scrollbar-thumb { background: #9C9A96; }

    /* LAYOUT */
    .auth-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      min-height: 100vh;
    }

    /* LEFT PANEL — editorial atmosphere */
    .visual-panel {
      position: relative;
      overflow: hidden;
      background: #0A0A0A;
    }
    .visual-media {
      position: absolute;
      inset: 0;
      background: url('https://images.unsplash.com/photo-1509631179647-0177331693ae?w=1400&q=90') center/cover no-repeat;
      opacity: 0.55;
      transform: scale(1.02);
      animation: slowZoom 18s ease-out forwards;
    }
    @keyframes slowZoom {
      to { transform: scale(1.08); }
    }
    .visual-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(145deg, rgba(10,10,10,0.2) 0%, rgba(10,10,10,0.65) 100%);
    }
    .visual-content {
      position: relative;
      z-index: 2;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 42px 36px;
    }
    .badge-row {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
    }
    .perk-badge {
      display: flex;
      align-items: center;
      gap: 8px;
      font-family: 'Space Grotesk', sans-serif;
      font-size: 9px;
      letter-spacing: 0.16em;
      text-transform: uppercase;
      color: rgba(248,246,242,0.7);
      padding: 8px 14px;
      border: 1px solid rgba(248,246,242,0.15);
      backdrop-filter: blur(2px);
    }

    /* RIGHT PANEL — forms */
    .form-container {
      display: flex;
      flex-direction: column;
      background: #F8F6F2;
      overflow-y: auto;
      position: relative;
    }
    .form-inner {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 56px 60px;
      max-width: 560px;
      margin: 0 auto;
      width: 100%;
    }

    /* TABS */
    .tab-bar {
      display: flex;
      gap: 0;
      border-bottom: 1px solid #EDEDEB;
      margin-bottom: 36px;
    }
    .tab-btn {
      flex: 1;
      text-align: center;
      font-family: 'Space Grotesk', sans-serif;
      font-size: 10px;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: #9C9A96;
      cursor: pointer;
      padding: 14px 0;
      border-bottom: 2px solid transparent;
      margin-bottom: -1px;
      transition: all 0.25s ease;
      background: none;
      border-top: none;
      border-left: none;
      border-right: none;
    }
    .tab-btn.active {
      color: #0A0A0A;
      border-bottom-color: #0A0A0A;
    }

    /* FORM STYLES */
    .input-group {
      margin-bottom: 20px;
      opacity: 0;
      transform: translateY(10px);
      animation: fadeUp 0.55s cubic-bezier(0.2, 0.9, 0.4, 1.1) forwards;
    }
    @keyframes fadeUp {
      to { opacity: 1; transform: translateY(0); }
    }
    .form-label {
      display: block;
      font-family: 'Space Grotesk', sans-serif;
      font-size: 9px;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: #9C9A96;
      margin-bottom: 8px;
    }
    .form-input {
      width: 100%;
      border: 1px solid #D5D3CF;
      background: #F8F6F2;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      font-weight: 300;
      padding: 13px 16px;
      outline: none;
      transition: border 0.2s, box-shadow 0.2s;
    }
    .form-input:focus {
      border-color: #0A0A0A;
      box-shadow: 0 0 0 3px rgba(10,10,10,0.05);
    }
    .form-input.error {
      border-color: #c0392b;
    }
    .error-msg {
      font-family: 'DM Sans', sans-serif;
      font-size: 11px;
      color: #c0392b;
      margin-top: 6px;
      display: none;
    }
    .error-msg.show {
      display: block;
    }
    .pw-wrapper {
      position: relative;
    }
    .pw-toggle {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      color: #9C9A96;
      transition: color 0.2s;
    }
    .pw-toggle:hover { color: #0A0A0A; }

    /* STRENGTH METER */
    .strength-wrap {
      margin-top: 8px;
    }
    .bars {
      display: flex;
      gap: 4px;
      margin-bottom: 6px;
    }
    .bar {
      flex: 1;
      height: 2px;
      background: #EDEDEB;
      transition: background 0.25s;
    }
    .strength-text {
      font-size: 10px;
      font-family: 'DM Sans', sans-serif;
      color: #9C9A96;
    }

    /* CHECKBOX */
    .checkbox-custom {
      width: 16px;
      height: 16px;
      border: 1px solid #D5D3CF;
      background: #F8F6F2;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s;
    }
    .checkbox-custom.checked {
      background: #0A0A0A;
      border-color: #0A0A0A;
    }
    .checkbox-custom svg {
      opacity: 0;
      transition: opacity 0.15s;
    }
    .checkbox-custom.checked svg {
      opacity: 1;
    }

    /* BUTTONS */
    .btn-primary {
      width: 100%;
      background: #0A0A0A;
      color: #F8F6F2;
      border: none;
      font-family: 'Space Grotesk', sans-serif;
      font-size: 11px;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      padding: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
    }
    .btn-primary:hover { background: #3D3D3A; }
    .btn-primary:disabled { background: #9C9A96; cursor: default; }
    .social-btn {
      width: 100%;
      border: 1px solid #D5D3CF;
      background: #F8F6F2;
      padding: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      cursor: pointer;
      transition: all 0.2s;
    }
    .social-btn:hover {
      border-color: #0A0A0A;
      background: #EDEDEB;
    }
    .or-divider {
      display: flex;
      align-items: center;
      gap: 16px;
      margin: 20px 0;
    }
    .or-divider::before, .or-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #EDEDEB;
    }

    /* FORGOT PANEL */
    .forgot-panel {
      position: absolute;
      inset: 0;
      background: #F8F6F2;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 56px 60px;
      max-width: 560px;
      margin: 0 auto;
      width: 100%;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s ease;
      z-index: 20;
    }
    .forgot-panel.show {
      opacity: 1;
      pointer-events: auto;
    }

    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: none;
      border: none;
      font-family: 'Space Grotesk', sans-serif;
      font-size: 10px;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: #9C9A96;
      margin-bottom: 28px;
      cursor: pointer;
    }
    .back-link:hover { color: #0A0A0A; }

    /* Two column grid for first/last */
    .name-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    @media (max-width: 768px) {
      .auth-grid { grid-template-columns: 1fr; }
      .visual-panel { display: none; }
      .form-inner, .forgot-panel { padding: 40px 24px; }
    }
  </style>
</head>
<body>

@php
  $authTab = old('_auth_tab', 'login');
  // If any registration-related error exists, default to register tab
  if ($errors->has('email') || $errors->has('password') || $errors->has('password_confirmation') || $errors->has('phone') || $errors->has('first_name') || $errors->has('last_name')) {
    $authTab = 'register';
  }
  // Retrieve old values for first name, last name, phone, email
  $firstNameOld = old('first_name', '');
  $lastNameOld = old('last_name', '');
  $phoneOld = old('phone', '');
  $emailOld = old('email', '');
  $rememberChecked = old('remember') ? 'checked' : '';
  $loginActive = $authTab === 'login';
  $registerActive = $authTab === 'register';
@endphp

<!-- MAIN LAYOUT -->
<div class="auth-grid">
  <!-- LEFT: VISUAL (unchanged) -->
  <div class="visual-panel">
    <div class="visual-media"></div>
    <div class="visual-overlay"></div>
    <div class="visual-content">
      <div>
        <div class="w-8 h-px bg-white/30 mb-6"></div>
        <p class="font-label text-[9px] text-white/40 tracking-[.22em]">MEMBERS-ONLY</p>
      </div>
      <div class="max-w-sm">
        <h2 class="font-display text-5xl md:text-6xl font-light text-white leading-[1.05] mb-5">Curated.<br>Effortless.<br><em class="not-italic">RYO.</em></h2>
        <p class="text-white/60 text-sm leading-relaxed mb-8">Join the inner circle — early access, reserved drops, and timeless style.</p>
        <div class="badge-row">
          <div class="perk-badge"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M5 12h14M12 5l7 7-7 7"/></svg>Early Access</div>
          <div class="perk-badge"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 014-4h14"/></svg>Free returns</div>
          <div class="perk-badge"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Order tracking</div>
        </div>
        <div class="flex items-center gap-4 mt-10 pt-6 border-t border-white/10">
          <div class="flex -space-x-2">
            <img class="w-8 h-8 rounded-full border border-white/40" src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=40&q=80" alt="member">
            <img class="w-8 h-8 rounded-full border border-white/40" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=40&q=80" alt="member">
            <img class="w-8 h-8 rounded-full border border-white/40" src="https://images.unsplash.com/photo-1527980965255-d3b416303d12?w=40&q=80" alt="member">
          </div>
          <p class="text-white/70 text-xs"><strong class="text-white">12k+</strong> style pioneers</p>
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT: FORM SECTION -->
  <div class="form-container" id="formContainer">
    <div class="form-inner" id="mainFormPanel">
      <!-- Tabs -->
      <div class="tab-bar">
        <button class="tab-btn {{ $loginActive ? 'active' : '' }}" id="loginTabBtn" onclick="switchAuthTab('login')">Sign In</button>
        <button class="tab-btn {{ $registerActive ? 'active' : '' }}" id="registerTabBtn" onclick="switchAuthTab('register')">Create Account</button>
      </div>

      <!-- LOGIN PANEL (unchanged) -->
      <div id="loginPanel" style="display: {{ $loginActive ? 'block' : 'none' }};">
        <div class="mb-8">
          <h1 class="font-display text-4xl md:text-5xl font-light tracking-tight mb-2">Welcome<br><em>back.</em></h1>
          <p class="text-ryo-gray-400 text-sm">Sign in to access your admin panel.</p>
        </div>



        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="_auth_tab" value="login">
            <div class="input-group"><label class="form-label">EMAIL</label><input class="form-input {{ $errors->has('email') ? 'error' : '' }}" type="email" name="email" id="loginEmail" placeholder="hello@ryo.com" autocomplete="email" value="{{ old('email') }}"><p class="error-msg {{ $errors->has('email') ? 'show' : '' }}">{{ $errors->first('email') }}</p></div>
            <div class="input-group"><label class="form-label">PASSWORD</label><div class="pw-wrapper"><input class="form-input {{ $errors->has('password') ? 'error' : '' }}" type="password" name="password" id="loginPass" placeholder="••••••••"><button class="pw-toggle" type="button" onclick="togglePassword('loginPass',this)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button></div><p class="error-msg {{ $errors->has('password') ? 'show' : '' }}">{{ $errors->first('password') }}</p></div>
            <div class="flex justify-between items-center mb-6">
              <div class="flex items-center gap-2 cursor-pointer" onclick="toggleCheckbox('rememberCheck')"><div class="checkbox-custom {{ $rememberChecked ? 'checked' : '' }}" id="rememberCheck"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#F8F6F2" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div><input type="checkbox" class="hidden" name="remember" id="rememberCheckInput" value="1" {{ $rememberChecked }}><span class="text-xs text-ryo-gray-400">Remember me</span></div>
              <button type="button" onclick="showForgot(true)" class="text-xs text-ryo-gray-400 hover:text-ryo-black transition">Forgot password?</button>
            </div>
            @if (session('status'))
                <div class="mt-4 p-3 border border-blue-200 bg-blue-50 text-blue-700 text-xs">{{ session('status') }}</div>
            @endif
            <button class="btn-primary" type="submit">Sign In <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button>
        </form>
        <p class="text-center text-sm text-ryo-gray-400 mt-6">New to RYO? <button type="button" onclick="switchAuthTab('register')" class="border-b border-ryo-gray-200 text-ryo-black">Create account</button></p>
      </div>

      <!-- REGISTER PANEL — simplified: first, last, email, phone, password, confirm -->
      <div id="registerPanel" style="display: {{ $registerActive ? 'block' : 'none' }};">
        <div class="mb-8">
          <h1 class="font-display text-4xl md:text-5xl font-light tracking-tight mb-2">Become a member</h1>
          <p class="text-ryo-gray-400 text-sm">Join RYO for early access and timeless style.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-5">
          @csrf
          <input type="hidden" name="_auth_tab" value="register">
          <!-- Hidden field to store combined name before submit -->
          <input type="hidden" name="name" id="combinedName" value="{{ trim(old('first_name', '') . ' ' . old('last_name', '')) }}">

          <!-- First name & Last name grid -->
          <div class="name-grid">
            <div>
              <label class="form-label">FIRST NAME</label>
              <input type="text" name="first_name" class="form-input @error('first_name') error @enderror" id="firstName" placeholder="Ahmed" value="{{ $firstNameOld }}">
              @error('first_name')<p class="error-msg show">{{ $message }}</p>@enderror
              <p class="error-msg" id="firstNameError">First name required</p>
            </div>
            <div>
              <label class="form-label">LAST NAME</label>
              <input type="text" name="last_name" class="form-input @error('last_name') error @enderror" id="lastName" placeholder="Mohamed" value="{{ $lastNameOld }}">
              @error('last_name')<p class="error-msg show">{{ $message }}</p>@enderror
              <p class="error-msg" id="lastNameError">Last name required</p>
            </div>
          </div>

          <!-- Email -->
          <div>
            <label class="form-label">EMAIL</label>
            <input type="email" name="email" class="form-input @error('email') error @enderror" id="regEmail" placeholder="hello@ryo.com" value="{{ $emailOld }}">
            @error('email')<p class="error-msg show">{{ $message }}</p>@enderror
            <p class="error-msg" id="emailError">Valid email required</p>
          </div>

          <!-- Phone (submitted to users table) -->
          <div>
            <label class="form-label">PHONE</label>
            <input type="tel" name="phone" class="form-input @error('phone') error @enderror" id="regPhone" placeholder="010XXXXXXXX" value="{{ $phoneOld }}">
            @error('phone')<p class="error-msg show">{{ $message }}</p>@enderror
            <p class="error-msg" id="phoneError">Phone is required and must start with 0</p>
          </div>

          <!-- Password -->
          <div>
            <label class="form-label">PASSWORD</label>
            <div class="pw-wrapper">
              <input type="password" name="password" class="form-input @error('password') error @enderror" id="regPassword" oninput="updateStrength()">
              <button class="pw-toggle" type="button" onclick="togglePassword('regPassword',this)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
            </div>
            <div class="strength-wrap" id="strengthBox" style="display: none;">
              <div class="bars"><div class="bar" id="bar1"></div><div class="bar" id="bar2"></div><div class="bar" id="bar3"></div><div class="bar" id="bar4"></div></div>
              <span class="strength-text" id="strengthLabel">—</span>
            </div>
            @error('password')<p class="error-msg show">{{ $message }}</p>@enderror
            <p class="error-msg" id="passwordError">Password must be at least 8 characters</p>
          </div>

          <!-- Confirm Password -->
          <div>
            <label class="form-label">CONFIRM PASSWORD</label>
            <div class="pw-wrapper">
              <input type="password" name="password_confirmation" class="form-input" id="regConfirm">
              <button class="pw-toggle" type="button" onclick="togglePassword('regConfirm',this)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
            </div>
            <p class="error-msg" id="confirmError">Passwords do not match</p>
          </div>

          <button class="btn-primary mt-4" type="submit" id="registerSubmitBtn">Create account →</button>
        </form>

        
        <p class="text-center text-xs text-ryo-gray-400 mt-6">By joining you agree to our <a href="#" class="border-b border-ryo-gray-300">Terms</a>.</p>
      </div>
    </div>

    <!-- FORGOT PASSWORD PANEL -->
    <div class="forgot-panel" id="forgotPanel">
      <button class="back-link" onclick="showForgot(false)"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M19 12H5M12 5l-7 7 7 7"/></svg> Back to sign in</button>
      <h2 class="font-display text-4xl font-light mb-2">Reset password</h2>
      <p class="text-sm text-ryo-gray-400 mb-6">Enter your email and we'll send a secure link.</p>
      <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
          @csrf
          <input class="form-input w-full" type="email" name="email" id="resetEmail" placeholder="you@ryo.com" value="{{ old('email') }}">
          @error('email')<p class="error-msg show">{{ $message }}</p>@enderror
          <button class="btn-primary" type="submit">Send reset link</button>
      </form>
      @if (session('status'))
          <div class="mt-4 bg-ryo-cream p-3 text-xs">{{ session('status') }}</div>
      @endif
    </div>
  </div>
</div>

<script>
  // Helper functions
  function togglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);
    if (!input) return;
    const type = input.type === 'password' ? 'text' : 'password';
    input.type = type;
  }

  function toggleCheckbox(id) {
    const checkbox = document.getElementById(id);
    checkbox.classList.toggle('checked');
    const hidden = document.getElementById(id + 'Input');
    if (hidden) hidden.checked = !hidden.checked;
  }

  function switchAuthTab(tab) {
    const loginPanel = document.getElementById('loginPanel');
    const regPanel = document.getElementById('registerPanel');
    const loginTab = document.getElementById('loginTabBtn');
    const regTab = document.getElementById('registerTabBtn');
    if (tab === 'login') {
      loginPanel.style.display = 'block';
      regPanel.style.display = 'none';
      loginTab.classList.add('active');
      regTab.classList.remove('active');
    } else {
      loginPanel.style.display = 'none';
      regPanel.style.display = 'block';
      regTab.classList.add('active');
      loginTab.classList.remove('active');
    }
  }

  function showForgot(show) {
    const panel = document.getElementById('forgotPanel');
    const main = document.getElementById('mainFormPanel');
    if (show) { panel.classList.add('show'); main.style.opacity = '0'; main.style.pointerEvents = 'none'; }
    else { panel.classList.remove('show'); main.style.opacity = '1'; main.style.pointerEvents = 'auto'; }
  }

  function socialMock(provider) {
    alert('Social sign-in with ' + provider + ' is not enabled yet.');
  }

  // Password strength meter
  function updateStrength() {
    const val = document.getElementById('regPassword').value;
    const meter = document.getElementById('strengthBox');
    if (!val) { meter.style.display = 'none'; return; }
    meter.style.display = 'block';
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const colors = ['#c0392b','#e67e22','#f1c40f','#27ae60'];
    const labels = ['Weak','Fair','Good','Strong'];
    for (let i=1;i<=4;i++) {
      let bar = document.getElementById(`bar${i}`);
      if (bar) bar.style.background = i <= score ? colors[score-1] : '#EDEDEB';
    }
    document.getElementById('strengthLabel').innerHTML = score ? labels[score-1] : '—';
  }

  // Registration form client-side validation + combine name before submit
  function validateRegistrationForm() {
    let isValid = true;

    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const email = document.getElementById('regEmail').value.trim();
    const phone = document.getElementById('regPhone').value.trim();
    const password = document.getElementById('regPassword').value;
    const confirm = document.getElementById('regConfirm').value;

    // Reset errors
    document.querySelectorAll('#registerPanel .error-msg').forEach(el => el.classList.remove('show'));

    if (!firstName) { document.getElementById('firstNameError').classList.add('show'); isValid = false; }
    if (!lastName) { document.getElementById('lastNameError').classList.add('show'); isValid = false; }

    const emailPattern = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
    if (!email || !emailPattern.test(email)) { document.getElementById('emailError').classList.add('show'); isValid = false; }

    if (!phone) { document.getElementById('phoneError').classList.add('show'); isValid = false; }
    else if (!/^0[0-9]{9,10}$/.test(phone)) { document.getElementById('phoneError').classList.add('show'); isValid = false; }

    if (password.length < 8) { document.getElementById('passwordError').classList.add('show'); isValid = false; }

    if (password !== confirm) { document.getElementById('confirmError').classList.add('show'); isValid = false; }

    return isValid;
  }

  // Override form submission: combine first+last into hidden name field, ensure phone is sent
  const registerForm = document.getElementById('registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', function(e) {
      if (!validateRegistrationForm()) {
        e.preventDefault();
        return;
      }
      // Combine first and last name into the hidden 'name' field
      const firstName = document.getElementById('firstName').value.trim();
      const lastName = document.getElementById('lastName').value.trim();
      const fullName = firstName + ' ' + lastName;
      const combinedInput = document.getElementById('combinedName');
      if (combinedInput) combinedInput.value = fullName;

      // Email and password fields already have name attributes
      // Proceed with normal submission
    });
  }

  // Optional: re-populate combined name on page load if old values exist
  window.addEventListener('DOMContentLoaded', function() {
    const first = document.getElementById('firstName')?.value || '';
    const last = document.getElementById('lastName')?.value || '';
    const combined = document.getElementById('combinedName');
    if (combined && (first || last)) combined.value = (first + ' ' + last).trim();
    // Trigger strength meter initial
    const pw = document.getElementById('regPassword');
    if (pw && pw.value) updateStrength();
  });
</script>
</body>
</html>
