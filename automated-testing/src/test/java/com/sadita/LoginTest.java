package com.sadita;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import static org.junit.jupiter.api.Assertions.*;

public class LoginTest extends BaseTest {

    @Test
    public void testTC01_LoginSuccess() {
        driver.get(baseUrl + "/login");
        
        WebElement emailInput = driver.findElement(By.id("email"));
        WebElement passwordInput = driver.findElement(By.id("password"));
        WebElement botCheckbox = driver.findElement(By.id("verify_bot"));
        WebElement submitBtn = driver.findElement(By.cssSelector("button[type='submit']"));
        
        // Enter valid credentials
        emailInput.clear();
        emailInput.sendKeys("admin");
        passwordInput.clear();
        passwordInput.sendKeys("admin");
        
        // Click bot verification
        if (!botCheckbox.isSelected()) {
            botCheckbox.click();
        }
        
        submitBtn.click();
        
        // Wait and check if redirected to admin page
        String currentUrl = driver.getCurrentUrl();
        assertTrue(currentUrl.contains("/admin"), "Should redirect to admin dashboard, but got: " + currentUrl);
    }

    @Test
    public void testTC02_LoginWrongCredentials() {
        driver.get(baseUrl + "/login");
        
        WebElement emailInput = driver.findElement(By.id("email"));
        WebElement passwordInput = driver.findElement(By.id("password"));
        WebElement botCheckbox = driver.findElement(By.id("verify_bot"));
        WebElement submitBtn = driver.findElement(By.cssSelector("button[type='submit']"));
        
        emailInput.clear();
        emailInput.sendKeys("wrongadmin");
        passwordInput.clear();
        passwordInput.sendKeys("wrongpass");
        
        if (!botCheckbox.isSelected()) {
            botCheckbox.click();
        }
        
        submitBtn.click();
        
        // Should stay on login page and display error
        String currentUrl = driver.getCurrentUrl();
        assertTrue(currentUrl.contains("/login") || currentUrl.endsWith("/login"), "Should stay on login page");
        
        // Check for validation error messages
        WebElement errorMsg = driver.findElement(By.xpath("//*[contains(text(), 'tidak cocok dengan catatan kami') or contains(text(), 'Credentials')]"));
        assertNotNull(errorMsg, "Error message should be shown");
    }

    @Test
    public void testTC03_LoginEmptyFields() {
        driver.get(baseUrl + "/login");
        
        WebElement emailInput = driver.findElement(By.id("email"));
        WebElement passwordInput = driver.findElement(By.id("password"));
        WebElement submitBtn = driver.findElement(By.cssSelector("button[type='submit']"));
        
        emailInput.clear();
        passwordInput.clear();
        
        // Clicking submit without filling required fields (HTML5 validation will prevent submission)
        submitBtn.click();
        
        // Url should remain same
        String currentUrl = driver.getCurrentUrl();
        assertTrue(currentUrl.contains("/login"), "Should stay on login page");
    }

    @Test
    public void testTC04_LoginWithoutBotCheck() {
        driver.get(baseUrl + "/login");
        
        WebElement emailInput = driver.findElement(By.id("email"));
        WebElement passwordInput = driver.findElement(By.id("password"));
        WebElement submitBtn = driver.findElement(By.cssSelector("button[type='submit']"));
        
        emailInput.clear();
        emailInput.sendKeys("admin");
        passwordInput.clear();
        passwordInput.sendKeys("admin");
        
        // Do NOT click the verify_bot checkbox
        submitBtn.click();
        
        // HTML5 validation requires the checkbox to be checked. URL stays the same.
        String currentUrl = driver.getCurrentUrl();
        assertTrue(currentUrl.contains("/login"), "Should not submit form and stay on login page");
    }
}
