<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>503 • Service Unavailable | Maintenance Mode</title>
    <!-- Professional Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(145deg, #f9fafb 0%, #f1f5f9 100%);
            color: #0f172a;
            min-height: 100vh;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            isolation: isolate;
        }

        /* premium card with glassmorphism touch */
        .error-card {
            max-width: 640px;
            width: 100%;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(0px);
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.03);
            padding: 2.75rem 2.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        @media (max-width: 560px) {
            .error-card {
                padding: 2rem 1.5rem;
            }
        }

        /* status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: #fff1f0;
            padding: 0.4rem 1.2rem 0.4rem 1.2rem;
            border-radius: 60px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: #b91c1c;
            border: 1px solid #ffcdd2;
            margin-bottom: 1.8rem;
        }

        .status-badge i {
            font-size: 0.8rem;
            color: #dc2626;
        }

        /* 503 visual - professional minimal */
        .error-code-visual {
            margin-bottom: 1.25rem;
        }

        .big-503 {
            font-size: 5.8rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #0f172a 0%, #475569 100%);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            display: inline-block;
        }

        .maintenance-icon {
            display: inline-block;
            background: #fef3c7;
            border-radius: 60px;
            padding: 0.2rem 0.8rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #b45309;
            margin-left: 0.75rem;
            vertical-align: middle;
        }

        h1 {
            font-size: 1.9rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #0f172a;
            margin: 0.75rem 0 1rem 0;
        }

        .description {
            color: #475569;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            font-weight: 400;
        }

        /* progress / eta bar - professional touch */
        .eta-section {
            background: #f1f5f9;
            border-radius: 1.25rem;
            padding: 1rem 1.25rem;
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
        }

        .eta-header {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.5rem;
            letter-spacing: 0.01em;
        }

        .progress-bar-container {
            background: #e2e8f0;
            border-radius: 60px;
            height: 6px;
            overflow: hidden;
            margin-bottom: 0.75rem;
        }

        .progress-fill {
            width: 68%;
            background: linear-gradient(90deg, #3b82f6, #6366f1);
            height: 100%;
            border-radius: 60px;
            position: relative;
            animation: shimmer 1.8s infinite linear;
            background-size: 200% 100%;
        }

        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .eta-message {
            font-size: 0.75rem;
            color: #5b6e8c;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* action group */
        .action-group {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .btn-primary {
            flex: 1;
            background: #0f172a;
            color: white;
            border: none;
            padding: 0.85rem 1.2rem;
            border-radius: 60px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .btn-primary i {
            font-size: 0.9rem;
        }

        .btn-primary:hover {
            background: #1e293b;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -8px rgba(15, 23, 42, 0.25);
        }

        .btn-outline {
            flex: 1;
            background: transparent;
            border: 1px solid #cbd5e1;
            padding: 0.85rem 1.2rem;
            border-radius: 60px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #1e293b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-outline i {
            font-size: 0.85rem;
        }

        .btn-outline:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            transform: translateY(-1px);
        }

        /* notification section */
        .notify-card {
            background: #fefce8;
            border-radius: 1rem;
            padding: 0.9rem 1.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.8rem;
            margin-bottom: 1.8rem;
            border: 1px solid #fde047;
        }

        .notify-text {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: #854d0e;
        }

        .notify-text i {
            font-size: 1rem;
        }

        .notify-btn {
            background: #ffffff;
            border: 1px solid #facc15;
            padding: 0.45rem 1.2rem;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #854d0e;
            cursor: pointer;
            transition: all 0.2s;
        }

        .notify-btn:hover {
            background: #fef9c3;
            transform: scale(0.98);
        }

        /* support footer */
        .support-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.8rem;
            border-top: 1px solid #eef2f6;
            padding-top: 1.5rem;
            margin-top: 0.5rem;
        }

        .support-text {
            font-size: 0.8rem;
            color: #5b6e8c;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .support-text i {
            color: #f97316;
        }

        .contact-link {
            background: none;
            border: none;
            font-weight: 600;
            font-size: 0.8rem;
            color: #0f172a;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: color 0.2s;
        }

        .contact-link:hover {
            color: #ea580c;
        }

        footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.7rem;
            color: #94a3b8;
        }

        /* animated bg */
        .bg-gradient-orb {
            position: fixed;
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, rgba(59,130,246,0.03) 0%, rgba(249,115,22,0.02) 100%);
            border-radius: 50%;
            bottom: -20vh;
            right: -15vw;
            pointer-events: none;
            z-index: -1;
        }

        .bg-gradient-orb-2 {
            position: fixed;
            top: -15vh;
            left: -10vw;
            width: 45vw;
            height: 45vw;
            background: radial-gradient(circle, rgba(139,92,246,0.02) 0%, rgba(59,130,246,0.01) 100%);
            border-radius: 50%;
            pointer-events: none;
            z-index: -1;
        }

        @media (max-width: 480px) {
            .big-503 {
                font-size: 3.8rem;
            }
            h1 {
                font-size: 1.5rem;
            }
            .action-group {
                flex-direction: column;
            }
            .notify-card {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<div class="bg-gradient-orb"></div>
<div class="bg-gradient-orb-2"></div>

<div class="error-container">
    <div class="error-card">
        
        <!-- badge status -->
        <div class="status-badge">
            <i class="fas fa-exclamation-triangle"></i>
            <span>SERVICE UNAVAILABLE · 503</span>
        </div>

        <!-- 503 hero -->
        <div class="error-code-visual">
            <div class="big-503">
                503
                <span class="maintenance-icon">
                    <i class="fas fa-tools"></i> Maintenance
                </span>
            </div>
        </div>

        <h1>Under scheduled maintenance</h1>
        <p class="description">
            Our servers are currently undergoing planned upgrades to serve you better. 
            The service will be restored shortly. We appreciate your patience.
        </p>

        <!-- ETA / progress simulation -->
        <div class="eta-section">
            <div class="eta-header">
                <span><i class="far fa-clock"></i> Estimated restoration</span>
                <span>~ 12 minutes remaining</span>
            </div>
            <div class="progress-bar-container">
                <div class="progress-fill"></div>
            </div>
            <div class="eta-message">
                <i class="fas fa-sync-alt fa-fw"></i>
                <span>Deploying critical performance improvements & security patches</span>
            </div>
        </div>

        <!-- primary actions -->
        <div class="action-group">
            <a href="{{ url('/') }}" class="btn-primary">
                <i class="fas fa-home"></i> 
                Return to Homepage
            </a>
            <button onclick="window.location.reload()" class="btn-outline">
                <i class="fas fa-rotate-right"></i> 
                Try again
            </button>
        </div>

        <!-- get notified when back online -->
        <div class="notify-card">
            <div class="notify-text">
                <i class="fas fa-bell"></i>
                <span>Get an email when service is restored</span>
            </div>
            <button id="notifyMeBtn" class="notify-btn">
                Notify me <i class="fas fa-arrow-right"></i>
            </button>
        </div>

        <!-- enterprise support & status -->
        <div class="support-meta">
            <div class="support-text">
                <i class="fas fa-chart-line"></i>
                <span>Active incident: INFRA-2025-04 · Monitoring in progress</span>
            </div>
            <div>
                <button id="statusPageBtn" class="contact-link">
                    <i class="fas fa-chart-simple"></i> Live status
                </button>
            </div>
        </div>

        <footer>
            <i class="fas fa-database"></i>  Scheduled maintenance window · ETA 20 min max — version 3.2.0 rollout
        </footer>
    </div>
</div>

<script>
    // professional utility: dynamic eta countdown simulation (just visual enhancement)
    const etaMinutesSpan = document.querySelector('.eta-header span:last-child');
    const progressFill = document.querySelector('.progress-fill');
    let minutesLeft = 12;
    const totalMinutes = 12;

    if (etaMinutesSpan && progressFill) {
        const interval = setInterval(() => {
            if (minutesLeft > 1) {
                minutesLeft--;
                etaMinutesSpan.innerText = `~ ${minutesLeft} minute${minutesLeft !== 1 ? 's' : ''} remaining`;
                const percent = ((totalMinutes - minutesLeft) / totalMinutes) * 100;
                progressFill.style.width = `${Math.min(100, percent + 10)}%`; 
            } else if (minutesLeft === 1) {
                etaMinutesSpan.innerText = `~ Few seconds remaining`;
                progressFill.style.width = '94%';
                clearInterval(interval);
                setTimeout(() => {
                    if (progressFill) progressFill.style.width = '100%';
                    if (etaMinutesSpan) etaMinutesSpan.innerText = 'Almost ready...';
                }, 3000);
            } else {
                clearInterval(interval);
            }
        }, 60000); // update every minute
    }

    // Notify Me button - elegant toast feedback (professional touch)
    const notifyBtn = document.getElementById('notifyMeBtn');
    if (notifyBtn) {
        notifyBtn.addEventListener('click', (e) => {
            e.preventDefault();
            // show a polished toast message
            const toast = document.createElement('div');
            toast.innerHTML = '<i class="fas fa-check-circle" style="margin-right: 8px;"></i> You\'ll be notified at your registered email once the service resumes.';
            toast.style.position = 'fixed';
            toast.style.bottom = '24px';
            toast.style.left = '50%';
            toast.style.transform = 'translateX(-50%)';
            toast.style.backgroundColor = '#0f172a';
            toast.style.color = '#f1f5f9';
            toast.style.padding = '12px 24px';
            toast.style.borderRadius = '48px';
            toast.style.fontSize = '0.85rem';
            toast.style.fontWeight = '500';
            toast.style.fontFamily = "'Inter', sans-serif";
            toast.style.zIndex = '1000';
            toast.style.boxShadow = '0 15px 30px -12px rgba(0,0,0,0.3)';
            toast.style.backdropFilter = 'blur(4px)';
            toast.style.border = '1px solid rgba(255,255,255,0.1)';
            toast.style.whiteSpace = 'nowrap';
            
            // For smaller screens adjust
            if (window.innerWidth < 550) {
                toast.style.whiteSpace = 'normal';
                toast.style.textAlign = 'center';
                toast.style.maxWidth = '85%';
                toast.style.fontSize = '0.75rem';
                toast.style.padding = '10px 18px';
            }
            
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.25s';
                setTimeout(() => toast.remove(), 400);
            }, 3000);
            
            // console log for debugging (professional)
            console.log('[503] User requested restoration notification');
        });
    }

    // Live status button simulation (corporate behavior)
    const statusBtn = document.getElementById('statusPageBtn');
    if (statusBtn) {
        statusBtn.addEventListener('click', () => {
            // open a simulated status page or show modal? we gracefully alert as modern approach
            const modalNotice = document.createElement('div');
            modalNotice.style.position = 'fixed';
            modalNotice.style.top = '50%';
            modalNotice.style.left = '50%';
            modalNotice.style.transform = 'translate(-50%, -50%)';
            modalNotice.style.backgroundColor = '#ffffff';
            modalNotice.style.padding = '1.5rem 2rem';
            modalNotice.style.borderRadius = '1.5rem';
            modalNotice.style.boxShadow = '0 30px 45px -15px rgba(0,0,0,0.2)';
            modalNotice.style.zIndex = '1001';
            modalNotice.style.maxWidth = '320px';
            modalNotice.style.textAlign = 'center';
            modalNotice.style.border = '1px solid #eef2f6';
            modalNotice.style.fontFamily = "'Inter', sans-serif";
            modalNotice.innerHTML = `
                <i class="fas fa-chart-line" style="font-size: 2rem; color: #3b82f6; margin-bottom: 0.8rem; display: block;"></i>
                <h4 style="font-weight: 700; margin-bottom: 0.5rem;">Current status</h4>
                <p style="font-size: 0.85rem; color: #475569; margin-bottom: 0.8rem;">All systems: <strong style="color:#eab308;">Maintenance in progress</strong><br>Backend upgrade, ETA ~10 min.</p>
                <button id="closeStatusBtn" style="background:#0f172a; color:white; border:none; padding:0.4rem 1.2rem; border-radius:40px; font-weight:500; cursor:pointer;">Close</button>
            `;
            document.body.appendChild(modalNotice);
            const closeBtn = document.getElementById('closeStatusBtn');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => modalNotice.remove());
            }
            setTimeout(() => {
                if (document.body.contains(modalNotice)) modalNotice.remove();
            }, 8000);
        });
    }

    // provide fallback for try again button (already has reload)
    // Additionally, add background loader friendly message
    window.addEventListener('load', () => {
        // preload cursor style, enhance UX
        const tryAgainBtn = document.querySelector('.btn-outline');
        if (tryAgainBtn && tryAgainBtn.innerText.includes('Try again')) {
            tryAgainBtn.addEventListener('click', (e) => {
                // tiny indicator for professional feedback
                const originalText = tryAgainBtn.innerHTML;
                tryAgainBtn.innerHTML = '<i class="fas fa-spinner fa-pulse"></i> Checking...';
                setTimeout(() => {
                    tryAgainBtn.innerHTML = originalText;
                }, 1000);
            });
        }
    });
    
    // optional: graceful console greeting
    console.log("%c🛠 503 | Enterprise maintenance mode — service restoration in progress", "color: #ea580c; font-weight: 600; font-size: 11px;");
</script>
</body>
</html>