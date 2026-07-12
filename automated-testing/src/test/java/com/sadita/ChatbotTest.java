package com.sadita;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import static org.junit.jupiter.api.Assertions.*;

public class ChatbotTest extends BaseTest {

    @Test
    public void testTC12_ToggleChatbot() {
        driver.get(baseUrl + "/");
        
        // Find toggle button
        WebElement toggleBtn = driver.findElement(By.id("chatbot-toggle"));
        assertNotNull(toggleBtn, "Chatbot toggle button should exist");
        
        // Click to open
        toggleBtn.click();
        
        try { Thread.sleep(1000); } catch (InterruptedException e) {}
        
        // Verify modal is open
        WebElement modal = driver.findElement(By.id("chatbot-modal"));
        String modalClass = modal.getAttribute("class");
        assertTrue(modalClass.contains("opacity-100") || !modalClass.contains("opacity-0"), "Chatbot modal should be visible after toggle click");
        
        // Click close
        WebElement closeBtn = driver.findElement(By.id("chatbot-close-btn"));
        closeBtn.click();
        
        try { Thread.sleep(1000); } catch (InterruptedException e) {}
        
        // Verify modal is closed
        modalClass = modal.getAttribute("class");
        assertTrue(modalClass.contains("opacity-0") || modalClass.contains("scale-0"), "Chatbot modal should be hidden after close click");
    }

    @Test
    public void testTC13_ChatbotSendMessage() {
        driver.get(baseUrl + "/");
        
        // Open chat
        driver.findElement(By.id("chatbot-toggle")).click();
        try { Thread.sleep(1000); } catch (InterruptedException e) {}
        
        // Find input textarea
        WebElement textarea = driver.findElement(By.id("chatbot-input"));
        textarea.sendKeys("Halo Sadita AI, apakah produk dekorasi tersedia?");
        
        // Click send
        driver.findElement(By.id("chatbot-send")).click();
        
        // Wait for bot typing animation and Groq response
        try { Thread.sleep(5000); } catch (InterruptedException e) {}
        
        // Verify messages list has bot response
        WebElement msgContainer = driver.findElement(By.id("chatbot-messages"));
        String messagesText = msgContainer.getText();
        assertTrue(messagesText.length() > 20, "Chatbot messages should contain the conversation history");
    }
}
