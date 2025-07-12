<style>
    /* Basic Reset and Layout */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Figtree', sans-serif;
        line-height: 1.6;
        color: #333;
        background: linear-gradient(135deg, #ED3D26 0%, #273F0B 50%, #161616 100%);
        background-attachment: fixed;
        min-height: 100vh;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Glass Effect */
    .glass-effect {
        backdrop-filter: blur(16px);
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    /* Custom Gradient Header */
    .custom-gradient-header {
        background: linear-gradient(135deg, #ED3D26 0%, #273F0B 50%, #161616 100%);
    }

    /* Payment Success Container */
    .payment-success-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }

    .success-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border-radius: 16px;
        overflow: hidden;
        max-width: 500px;
        width: 100%;
        text-align: center;
        animation: slideUp 0.6s ease-out;
    }

    .success-icon {
        background: linear-gradient(135deg, #ED3D26 0%, #273F0B 50%, #161616 100%);
        color: white;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 40px auto 30px;
        font-size: 2rem;
        box-shadow: 0 10px 30px rgba(237, 61, 38, 0.3);
        animation: pulse 2s infinite;
        transition: transform 0.3s ease;
    }

    .error-icon {
        background: linear-gradient(135deg, #dc3545 0%, #a71e2a 100%);
        color: white;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 40px auto 30px;
        font-size: 2rem;
        box-shadow: 0 10px 30px rgba(220, 53, 69, 0.3);
        animation: shake 0.5s ease-in-out;
        transition: transform 0.3s ease;
    }

    .status-title {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
    }

    .success-title {
        background: linear-gradient(135deg, #ED3D26 0%, #273F0B 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .error-title {
        background: linear-gradient(135deg, #dc3545 0%, #a71e2a 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .success-message, .status-message {
        color: #6b7280;
        font-size: 1.1rem;
        margin-bottom: 30px;
        line-height: 1.6;
        padding: 0 20px;
    }

    .info-box {
        background: linear-gradient(135deg, rgba(237, 61, 38, 0.1) 0%, rgba(39, 63, 11, 0.1) 100%);
        border-radius: 12px;
        padding: 25px;
        margin: 30px 20px;
        border: 1px solid rgba(237, 61, 38, 0.2);
    }

    .info-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .info-title::before {
        content: "🎯";
        margin-right: 8px;
    }

    .info-list {
        list-style: none;
        text-align: left;
    }

    .info-list li {
        color: #4b5563;
        margin-bottom: 10px;
        padding-left: 8px;
        font-weight: 500;
    }

    .button-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin: 30px 20px;
    }

    .contact-info {
        background: rgba(249, 250, 251, 0.8);
        border-radius: 8px;
        padding: 20px;
        margin: 20px;
        border-top: 1px solid rgba(229, 231, 235, 0.8);
    }

    .contact-info p {
        color: #6b7280;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .contact-info a {
        color: #ED3D26;
        text-decoration: none;
        font-weight: 600;
    }

    .contact-info a:hover {
        color: #c73321;
        text-decoration: underline;
    }

    .btn {
        display: inline-block;
        padding: 12px 30px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 1rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #ED3D26 0%, #273F0B 100%);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #c73321 0%, #1f2f08 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(237, 61, 38, 0.3);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #5a6268 0%, #374151 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(107, 114, 128, 0.3);
    }

    /* Animations */
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    @keyframes shake {
        0%, 100% {
            transform: translateX(0);
        }
        25% {
            transform: translateX(-5px);
        }
        75% {
            transform: translateX(5px);
        }
    }

    /* Font Awesome Icons */
    .fas, .fab {
        font-weight: 900;
    }

    /* Additional utility classes */
    .text-center {
        text-align: center;
    }

    .font-bold {
        font-weight: bold;
    }

    .font-semibold {
        font-weight: 600;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .text-lg {
        font-size: 1.125rem;
    }

    /* Admin Access Link */
    .admin-access {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #007bff;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .admin-access:hover {
        background: #0056b3;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .success-card, .payment-success-container .success-card {
            padding: 30px 20px;
            margin: 10px;
        }

        .success-icon, .error-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
            margin: 30px auto 20px;
        }

        .status-title, .success-title {
            font-size: 1.5rem;
        }

        .success-message, .status-message {
            font-size: 1rem;
            padding: 0 10px;
        }

        .info-box {
            margin: 20px 10px;
            padding: 20px;
        }

        .button-container {
            margin: 20px 10px;
            gap: 12px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 0.9rem;
        }

        .contact-info {
            margin: 15px 10px;
            padding: 15px;
        }

        .admin-access {
            top: 10px;
            right: 10px;
            padding: 8px 15px;
            font-size: 0.9rem;
        }
    }

    /* Mobile specific optimizations for registration form */
    @media (max-width: 480px) {
        .custom-gradient-header {
            padding: 1rem !important;
        }
        
        .form-card {
            margin: 0.5rem;
        }
        
        .glass-effect {
            border-radius: 1rem !important;
        }
        
        /* Mobile logo sizing for better proportionality */
        .custom-gradient-header img {
            height: 2rem !important; /* 32px */
            max-width: 80px !important;
        }
    }

    /* Extra small mobile devices (320px) */
    @media (max-width: 320px) {
        .custom-gradient-header {
            padding: 0.75rem !important;
        }
        
        .custom-gradient-header h1 {
            font-size: 1.125rem !important;
            line-height: 1.5 !important;
        }
        
        .custom-gradient-header p {
            font-size: 0.875rem !important;
        }
        
        .form-card {
            margin: 0.25rem;
        }
        
        /* Extra small logo sizing for 320px width */
        .custom-gradient-header img {
            height: 1.5rem !important; /* 24px */
            max-width: 60px !important;
        }
        
        /* Reduce spacing between logos */
        .custom-gradient-header .flex.space-x-4 {
            gap: 0.5rem !important;
        }
    }
</style>
