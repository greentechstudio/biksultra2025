#!/bin/bash
# Xendit Webhook Fix Script for VPS Ubuntu

echo "🔧 Fixing Xendit Webhook on VPS..."
echo "=================================="

# 1. Check environment
echo "1. 🔍 Checking environment configuration..."
php artisan xendit:health-check

echo ""
echo "2. 👥 Creating test user with external_id..."
php artisan user:create-test --external-id=invoice_123124123

echo ""
echo "3. 🧪 Testing webhook processing..."
php artisan xendit:webhook-debug invoice_123124123

echo ""
echo "4. 🌐 Testing webhook endpoint with curl..."
curl -X POST https://amazingsultrarun.com/api/xendit/webhook \
  -H 'Content-Type: application/json' \
  -H 'X-CALLBACK-TOKEN: RhrNV1zqzSj8frwjaueNb52nCNp7Cz08F4bJmp1QzxHosXiJ' \
  -d '{
    "id": "test-webhook-fix-'$(date +%s)'",
    "external_id": "invoice_123124123",
    "status": "PAID",
    "payment_method": "BANK_TRANSFER",
    "amount": 50000,
    "paid_amount": 50000,
    "paid_at": "'$(date -Iseconds)'",
    "payer_email": "test@example.com",
    "description": "Test webhook fix"
  }'

echo ""
echo ""
echo "5. 📋 Checking recent logs..."
echo "Recent webhook logs:"
grep -i "webhook\|xendit" storage/logs/laravel.log | tail -10

echo ""
echo "✅ Webhook fix script completed!"
echo ""
echo "🎯 Next steps:"
echo "1. If curl test returns {\"success\": true}, webhook is working"
echo "2. Go to Xendit dashboard and test webhook again"
echo "3. Check logs with: tail -f storage/logs/laravel.log"
