
               <h2>Confirm Your Newsletter Subscription</h2>

               <p>Hi {{ $subscriber->name ?? 'there' }},</p>

               <p>Thank you for signing up for our newsletter! Please confirm your subscription by clicking the link below:</p>

               <p>
                <a href="{{ route('newsletter.confirm', $subscriber->confirmation_token) }}" style="display: inline-block; padding: 12px 24px; background-color: #4f46e5; color: white; text-decoration: none; border-radius: 5px;">
                    Confirm Subscription
                    </a>
                    </p>

                    <p>Or copy and paste this link into your browser:</p>
                    <p>{{ route('newsletter.confirm', $subscriber->confirmation_token) }}</p>

                    <p>This link will expire in 48 hours.</p>

                    <hr>
                    
                    <p style="font-size: 12px; color: #666;">If you didn't sign up for this newsletter, you can safely ignore this email.</p>
                    