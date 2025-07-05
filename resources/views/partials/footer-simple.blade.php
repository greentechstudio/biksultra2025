{{-- Simple Footer --}}
<footer style="background: #1e3a4a; color: white; padding: 60px 0 30px; text-align: center;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div style="margin-bottom: 40px;">
            <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 20px;">
                <div style="background: #ef4444; color: white; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 24px; border-radius: 4px;">R</div>
                <div>
                    <h3 style="font-size: 32px; font-weight: bold; margin: 0;">RUNER</h3>
                    <p style="font-size: 11px; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 2px; margin: 0;">Running Club</p>
                </div>
            </div>
            <h2 style="font-size: 2.5rem; font-weight: bold; margin-bottom: 20px; text-transform: uppercase;">Start Running With Us Today!</h2>
            <p style="font-size: 16px; color: rgba(255,255,255,0.8); max-width: 500px; margin: 0 auto;">Join our community of passionate runners and discover the joy of running together.</p>
        </div>
        
        <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                <div style="display: flex; gap: 30px; flex-wrap: wrap;">
                    <a href="#" style="color: rgba(255,255,255,0.8); text-decoration: none; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">Home</a>
                    <a href="#" style="color: rgba(255,255,255,0.8); text-decoration: none; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">About</a>
                    <a href="#" style="color: rgba(255,255,255,0.8); text-decoration: none; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">Events</a>
                    <a href="#" style="color: rgba(255,255,255,0.8); text-decoration: none; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">Contact</a>
                </div>
                <p style="font-size: 14px; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1px; margin: 0;">© 2025 Runer Running Club. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<style>
    @media (max-width: 768px) {
        footer div[style*="display: flex; justify-content: space-between"] {
            flex-direction: column !important;
            text-align: center !important;
            gap: 20px !important;
        }
        
        footer div[style*="display: flex; gap: 30px"] {
            justify-content: center !important;
        }
        
        footer h2[style*="font-size: 2.5rem"] {
            font-size: 2rem !important;
        }
    }
</style>
