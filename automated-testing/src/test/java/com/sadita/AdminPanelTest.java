package com.sadita;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import static org.junit.jupiter.api.Assertions.*;

public class AdminPanelTest extends BaseTest {

    @Test
    public void testTC14_AdminPanelNavigation() {
        // Go to login page
        driver.get(baseUrl + "/login");
        
        // Fill login details
        driver.findElement(By.name("email")).sendKeys("admin@sadita.com");
        driver.findElement(By.name("password")).sendKeys("Admin@1234");
        
        // Click robot check if present
        try {
            WebElement robotCheckbox = driver.findElement(By.id("robot"));
            if (robotCheckbox != null && !robotCheckbox.isSelected()) {
                robotCheckbox.click();
            }
        } catch (Exception e) {}
        
        // Submit
        driver.findElement(By.cssSelector("button[type='submit']")).click();
        
        try { Thread.sleep(3000); } catch (InterruptedException e) {}
        
        // Verify dashboard URL
        String currentUrl = driver.getCurrentUrl();
        assertTrue(currentUrl.contains("/admin"), "Should redirect to admin panel dashboard, but got: " + currentUrl);
        
        // Navigate to Products Resource
        driver.get(baseUrl + "/admin/produks");
        try { Thread.sleep(2000); } catch (InterruptedException e) {}
        assertTrue(driver.getCurrentUrl().contains("/admin/produks"), "Should navigate to products page");
        
        // Navigate to Orders Resource
        driver.get(baseUrl + "/admin/pesanans");
        try { Thread.sleep(2000); } catch (InterruptedException e) {}
        assertTrue(driver.getCurrentUrl().contains("/admin/pesanans"), "Should navigate to orders page");
        
        // Navigate to Testimonials Resource
        driver.get(baseUrl + "/admin/ulasans");
        try { Thread.sleep(2000); } catch (InterruptedException e) {}
        assertTrue(driver.getCurrentUrl().contains("/admin/ulasans"), "Should navigate to ulasans page");
    }
}
